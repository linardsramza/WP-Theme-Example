<?php

/**
 * Workshop list Block Template.
 */

$id = '';
$className = 'block workshop-list';

if ( isset( $args['blockData'] ) ) :
	$id = $args['blockData']['id'];
	$className .= ' ' . $args['blockData']['className'];
endif;

// Block values.
$title_args = $args['title_args'];
$intro = $args['intro'];
$workshop_list = $args['workshops'];
$workshops_title = $args['workshops_title'];
$workshops_title_settings = [
	'tag' => $workshops_title['level'],
	'class' => $workshops_title['style'] . ' workshop-list__card--title'
];
$service_code = $args['service_code'];
?>
<section id="<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($className); ?>">
	<div class="container">

		<?php if($intro) { ?>
			<div class="workshop-list__intro">
				<div class="row">
					<div class="row__col row__col--lg-8 row__col--md-10">
						<?php echo Helper::generate_header($title_args); ?>
						<?php echo Helper::generate_paragraph(['text'  => $intro]); ?>
					</div>
				</div>
			</div>
		<?php } ?>

		<?php if($workshop_list) { ?>
			<div class="row">
				<?php foreach($workshop_list as $workshop) {
					$workshop_link = get_the_permalink($workshop);
					$hide_rating = get_field('hide_rating', $workshop) ?? false;
					$rating = get_field('average_rating', $workshop) ?? 0;
					$workshops_title_settings['text'] = get_the_title($workshop);
					$workshops_title_settings['link_url'] = $workshop_link;
					$title = Helper::generate_header($workshops_title_settings);
					$address = get_field('workshop_address', $workshop);
					if($address) {
						$address = $address['address'] . ', ' . $address['zip_code'] . ' ' . $address['city'];
					}
					$contact_info = get_field('contact_info', $workshop);

					$phone = $contact_info['phone_number'] ?? '';
					$phone_args = [];
					// Its possible to have multiple phone nr
					if($phone) {
						$phone_number = explode(',', $phone);
						$phone_number = Helper::get_phone_link($phone_number[0]);
						$phone_args = [
							'text'   => '<i class="fa-solid fa-phone"></i> ' . $phone,
							'link'   => 'tel:' . $phone_number,
							'class'  => 'phone',
							'title'  => $phone_number,
						];
					}

					$email = $contact_info['email'] ?? '';
					$email_args = [];
					if($email) {
						$email_args = [
							'text'   => '<i class="fa-solid fa-envelope"></i> ' .  $email,
							'link'   => 'mailto:' . $email,
							'class'  => 'email',
							'title'  => $email,
						];
					}

					$booking_args = [
						'text'   => __('Book a spot', THEME_NAME),
						'link'   => Helper::get_booking_link($workshop, $service_code),
						'class'  => 'btn btn--yellow btn--small',
						'title'  => __('Book a spot', THEME_NAME),
					];
                    $book_time_page_type = get_field('book_time_page_type', 'options');
                    if($book_time_page_type === 'external-site') {
                        $booking_args['target'] = "_blank";
                    }


					$workshop_link_args = [
						'text'   => __('More info about the workshop', THEME_NAME),
						'link'   => $workshop_link,
						'class'  => 'btn btn--secondary btn--small',
						'title'  => __('More info about the workshop', THEME_NAME),
					];

					?>
					<div class="row__col row__col--sm-6 row__col--lg-4 workshop-list__card-col">
						<div class="workshop-list__card">
							<?php
								// if(!$hide_rating && $rating > 0 ) {
            					// 	echo Helper::show_workshop_rating_stars($rating);
								// }
							?>
							<?php echo $title; ?>
							<?php if($address) { ?>
								<p class="workshop-list__card--address"><?php echo $address; ?></p>
							<?php } ?>
							<div class="workshop-list__card--contact-info">
								<?php echo Helper::generate_link($phone_args); ?>
								<?php echo Helper::generate_link($email_args); ?>
							</div>
							<div class="workshop-list__card--links">
								<?php echo Helper::generate_link($booking_args); ?>
								<?php echo Helper::generate_link($workshop_link_args); ?>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		<?php } ?>

	</div>
</section>