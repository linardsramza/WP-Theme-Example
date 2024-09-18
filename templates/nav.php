<?php 

// Menu variables
$main_menu = get_field('menu', 'options');
$logo = get_field('logo', 'options');
$promo_mobile = get_field('promo_mobile', 'options');
$current_page_url = get_permalink();
$active_class = '';

$logo = Helper::generate_image(array(
	'image'           => $logo,
	'image_size'      => '',
	'figure'          => true,
	'figure_class'    => 'header__logo',
	'link'            => get_home_url(),
	'link_attributes' => array( 'aria-label' => get_bloginfo( 'name' ) )
));

$book_button_url = Helper::get_booking_link(get_the_ID());
$book_button_text = get_field('book_button_title', 'options') ?? __('Make an appointment', THEME_NAME);
$book_time_page_type = get_field('book_time_page_type', 'options') ?? 'current-site';
$booking_button_target = $book_time_page_type !== 'current-site' ? '_blank' : false;
$book_button = Helper::generate_acf_link(array(
    'link'  => [
        'title' => $book_button_text,
        'url' => $book_button_url,
        'target' => $booking_button_target
    ],
    'class' => 'btn btn--yellow btnBook',
));

?>
<header class="site-header">
	<div class="container">
	<div class="site-header__inner">

<?php echo $logo; ?>

<?php if($main_menu) : ?>

	<nav class="main__nav">

		<?php get_template_part( 'templates/nav', 'search-bar', array( 'is_mobile' => true) ); ?>

		<ul class="navbar-nav menu">
			<?php foreach($main_menu as $item) : 
				$page = $item['page'];
				$menu_type = $item['submenu']['menu_type'];
				$menu_type_class = '';
				$promo = $item['submenu']['promo'];
				$sub_items = $item['submenu']['submenu'];
				$submenu_title = $item['submenu']['submenu_title'];
				$item_direction = $item['submenu']['item_direction'];

				$submenu_title = Helper::generate_header(array(
					'text'        => $submenu_title,
					'tag'         => 'h4',
					'class'       => 'h4 submenu__title',
				));

				if ($menu_type) :
					$menu_type_class = 'services-listing';
				endif;

				if ($current_page_url === $page['url']) : 
					$active_class = 'menu__item--active';
				else :
					$active_class = '';
				endif;

				if ($page['url'] && $page['title']) :
				?>
				<li class="menu__item <?php echo !empty($sub_items) ? 'menu__item--subitems' : ''; ?> <?php echo $active_class; ?>">
					<a href="<?php echo $page['url'] ? $page['url'] : ''; ?>" class="js-show-services-desktop <?php echo empty($sub_items) ? 'menu__item--empty' : ''; ?>"><?php echo $page['title'] ? $page['title'] : ''; ?></a>
					<?php if (!empty($sub_items)) : ?>
						<a href="#" class="show-services js-show-services">
							<i class="fas fa-chevron-down"></i>
						</a>
						<div class="submenu__container <?php echo $menu_type_class; ?>">
							<div class="submenu__container--inner <?= !empty($promo) ? 'has-promo' : ''; ?>">
								<div class="submenu__section">
									
								<?php 
									if ($menu_type) : 
										echo $submenu_title;
									endif;
								?>

								<ul class="submenu <?= !$menu_type ? 'submenu--' . $item_direction : ''; ?>">
									<?php foreach ($sub_items as $sub_item) : 
										if (!empty($sub_item['sub_page'])) :
										$sub_page = $sub_item['sub_page'];
										$icons = $sub_item['icons'];

										if ($current_page_url === $sub_page['url']) : 
											$active_class = 'submenu__item--active';
										else :
											$active_class = '';
										endif;

										$svg = Helper::generate_image(array(
											'image'           => $icons['icon_svg'],
											'image_size'      => 'full',
											'figure'          => false
										));

											?>
											<li class="submenu__item <?php echo $active_class; ?>">
												<a href="<?php echo $sub_page['url']; ?>">
													<?php echo $menu_type ? $svg : ''; ?>
													<?php echo $sub_page['title']; ?>
												</a>
											</li>
										<?php 
										endif;
									endforeach; ?>
								</ul>
								</div>

							<?php if($promo) : 
								get_template_part( 'templates/nav', 'promo', array( 'is_mobile' => false, 'promo_count' => count($promo), 'promo_loop' => $promo) );
							endif; ?>
							</div>
						</div>
					<?php endif; ?>
				</li>
			<?php 
			endif;
			endforeach; ?>
		</ul>

		<?php if($promo_mobile) :
			get_template_part( 'templates/nav', 'promo', array( 'is_mobile' => true, 'promo_count' => count($promo_mobile), 'promo_loop' => $promo_mobile) );
		endif; ?>

	</nav>

	<div class="site-header__right">
		<?php if ($book_button) : ?>
			<?php echo $book_button; ?>
		<?php endif; ?>
		<a href="#" class="site-header__right--search">
			<span></span>
		</a>
		<button class="site-header__icon site-header__icon--hamburger js-show-hamburger" aria-label="Open mobile menu">
			<span></span>
		</button>
	</div>

	<?php get_template_part( 'templates/nav', 'search-bar', array( 'is_mobile' => false) ); ?>
	
<?php endif; ?>

</div>
	</div>
</header>
