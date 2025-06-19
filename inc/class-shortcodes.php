<?php 

class Register_Shortcodes
{
    public function __construct()
    {
        // City CPT short codes
        add_shortcode('city_name', [$this, 'city_name']);
        add_shortcode('city_workshop_count', [$this, 'city_workshop_count']);
        add_shortcode('service_name', [$this, 'service_name']);
        add_shortcode('service_name_lowercase', [$this, 'service_name_lowercase']);
        add_shortcode('service_workshop_count', [$this, 'service_workshop_count']);
        add_shortcode('workshop_name', [$this, 'workshop_name']);
        add_shortcode('workshop_street', [$this, 'workshop_street']);
        add_shortcode('workshop_zipcode', [$this, 'workshop_zipcode']);
        add_shortcode('workshop_city', [$this, 'workshop_city']);
        add_shortcode('workshop_phone', [$this, 'workshop_phone']);
        add_shortcode('workshop_email', [$this, 'workshop_email']);
        add_shortcode('workshop_description', [$this, 'workshop_description']);
        add_shortcode('workshop_image', [$this, 'workshop_image']);
        add_shortcode('warehouse_name', [$this, 'warehouse_name']);
        add_shortcode('warehouse_street', [$this, 'warehouse_street']);
        add_shortcode('warehouse_zipcode', [$this, 'warehouse_zipcode']);
        add_shortcode('warehouse_city', [$this, 'warehouse_city']);
        add_shortcode('warehouse_phone', [$this, 'warehouse_phone']);
        add_shortcode('warehouse_email', [$this, 'warehouse_email']);
        add_shortcode('booking_link', [$this, 'booking_link']);
        add_shortcode('booking_button', [$this, 'booking_button']);
        add_shortcode('total_workshop_count', [$this, 'total_workshop_count']);
        add_shortcode('monday_working_hours', [$this, 'workshop_working_hours']);
        add_shortcode('tuesday_working_hours', [$this, 'workshop_working_hours']);
        add_shortcode('wednesday_working_hours', [$this, 'workshop_working_hours']);
        add_shortcode('thursday_working_hours', [$this, 'workshop_working_hours']);
        add_shortcode('friday_working_hours', [$this, 'workshop_working_hours']);
        add_shortcode('saturday_working_hours', [$this, 'workshop_working_hours']);
        add_shortcode('sunday_working_hours', [$this, 'workshop_working_hours']);
        add_shortcode('lunchtime_working_hours', [$this, 'workshop_working_hours']);
        add_shortcode('special_opening_hours', [$this, 'special_opening_hours']);

        // Add shortcodes to SEOPress meta title and description
        add_filter('seopress_titles_template_variables_array', [$this, 'seopress_titles_variables_array']);
        add_filter('seopress_titles_template_replace_array', [$this, 'seopress_titles_variables_replace_array']);

    }

    public function city_name() {
        $post_ID = get_the_ID();
        $city_ID = get_field('city', $post_ID);
        $city_name = '';
        if($city_ID) {
            $city_name = get_term($city_ID)->name;
        }
        return $city_name;
    }

    public function city_workshop_count() {
        $post_ID = get_the_ID();
        $city_ID = get_field('city', $post_ID);
        $workshop_count = '';
        if($city_ID) {
            $workshop_count = get_term($city_ID)->count;
        }
        return $workshop_count;
    }

    public function service_name()
    {
        $post_ID = get_the_ID();
        $service_name = get_field('service_name', $post_ID) ?? '';
        return $service_name;
    }

    public function service_name_lowercase()
    {
        $post_ID = get_the_ID();
        $service_name = get_field('service_name', $post_ID) ?? '';
        $service_name = strtolower($service_name);
        return $service_name;
    }

    public function service_workshop_count()
    {
        $post_ID = get_the_ID();
        $service_name = get_field('service_name', $post_ID) ?? '';
        $city = get_field('city', $post_ID) ?? '';
        $workshops_count = 0;
        if(!empty($service_name)) {
            $args = [
                'post_type' => 'workshops',
                'posts_per_page' => -1,
                'tax_query' => [
                    'relation' => 'AND',
                    [
                        'taxonomy' => 'services',
                        'field' => 'name',  
                        'terms' => $service_name,
                    ],
                    [
                        'taxonomy' => 'workshops-cities',
                        'field' => 'term_id',
                        'terms' => $city,
                    ]
                ]
            ];
            // Get the posts
            $workshops = get_posts( $args );
            $workshops_count = count($workshops);
        }
        return $workshops_count;
    }

    public function workshop_name() {
        $workshop_name = get_the_title();
        return $workshop_name;
    }

    public function workshop_street() {
        $post_ID = get_the_ID();
        $workshop_address = get_field('workshop_address', $post_ID);
        $workshop_street = '';
        if(isset($workshop_address['address'])) {
            $workshop_street = $workshop_address['address'];
        }
        return $workshop_street;
    }

    public function workshop_zipcode() {
        $post_ID = get_the_ID();
        $workshop_address = get_field('workshop_address', $post_ID);
        $workshop_zipcode = '';
        if(isset($workshop_address['zip_code'])) {
            $workshop_zipcode = $workshop_address['zip_code'];
        }
        return $workshop_zipcode;
    }

    public function workshop_city() {
        $post_ID = get_the_ID();
        $workshop_address = get_field('workshop_address', $post_ID);
        $workshop_city = '';
        if(isset($workshop_address['city'])) {
            $workshop_city = $workshop_address['city'];
        }
        return $workshop_city;
    }

    public function workshop_phone() {
        $post_ID = get_the_ID();
        $contact_info = get_field('contact_info', $post_ID);
        $workshop_phone = '';
        if(isset($contact_info['phone_number'])) {
            $workshop_phone = $contact_info['phone_number'];
            $workshop_phone = explode(',', $workshop_phone)[0];
        }
        return $workshop_phone;
    }

    public function workshop_email() {
        $post_ID = get_the_ID();
        $contact_info = get_field('contact_info', $post_ID);
        $workshop_email = '';
        if(isset($contact_info['email'])) {
            $workshop_email = $contact_info['email'];
        }
        return $workshop_email;
    }

    public function workshop_description() {
        $post_ID = get_the_ID();
        $description = get_field('description', $post_ID) ?? '';
        $description = apply_filters( 'the_content', $description );
        return $description;
    }

    public function workshop_image($atts) {
        $post_ID = get_the_ID();
        $workshop_image = '';
        $workshop_img_id = get_field('workshop_image', $post_ID);
        $image_size = $atts['size'] ?? 'full';
        if(!$workshop_img_id) {
            $workshop_img_id = get_field('workshop_placeholder_image', 'options');
        }
        if($workshop_img_id) {
            $workshop_image = wp_get_attachment_image($workshop_img_id, $image_size, false, ['class' => 'rounded-corners']);
        }
        return $workshop_image;
    }

    public function warehouse_name() {
        $warehouse_name = get_the_title();
        return $warehouse_name;
    }

    public function warehouse_street() {
        $post_ID = get_the_ID();
        $address = get_field('workshop_address', $post_ID);
        $warehouse_street = '';
        if(isset($address['address'])) {
            $warehouse_street = $address['address'];
        }
        return $warehouse_street;
    }

    public function warehouse_zipcode() {
        $post_ID = get_the_ID();
        $address = get_field('workshop_address', $post_ID);
        $warehouse_zipcode = '';
        if(isset($address['zip_code'])) {
            $warehouse_zipcode = $address['zip_code'];
        }
        return $warehouse_zipcode;
    }

    public function warehouse_city() {
        $post_ID = get_the_ID();
        $address = get_field('workshop_address', $post_ID);
        $warehouse_city = '';
        if(isset($address['city'])) {
            $warehouse_city = $address['city'];
        }
        return $warehouse_city;
    }

    public function warehouse_phone() {
        $post_ID = get_the_ID();
        $address = get_field('contact_info', $post_ID);
        $warehouse_phone = '';
        if(isset($address['phone_number'])) {
            $warehouse_phone = $address['phone_number'];
            $workshop_phone = explode(',', $warehouse_phone)[0];
        }
        return $warehouse_phone;
    }

    public function warehouse_email() {
        $post_ID = get_the_ID();
        $address = get_field('contact_info', $post_ID);
        $warehouse_email = '';
        if(isset($address['email'])) {
            $warehouse_email = $address['email'];
        }
        return $warehouse_email;
    }

    public function booking_link() {
        $post_ID = get_the_ID();
        $booking_link = Helper::get_booking_link($post_ID);
        return $booking_link;
    }

    public function booking_button($atts) {
        $post_ID = get_the_ID();
        $booking_btn_title = $atts['title'] ?? __( 'Send request', THEME_NAME );
        $booking_btn_target = $atts['target'] ?? false;
        $booking_link_href = Helper::get_booking_link($post_ID);
        $booking_link = [
            'text'  => $booking_btn_title,
            'link'  => $booking_link_href,
            'target'=> $booking_btn_target,
            'class' => 'btn btn--primary btn--booking',
        ];

        return Helper::generate_link($booking_link);
    }

    public function total_workshop_count() {
        $workshop_count = wp_count_posts('workshops')->publish;
        return $workshop_count;
    }

    public function workshop_working_hours($atts, $content, $shortcode_tag)
    {
        $time_separator = ' - ';
        $post_ID = get_the_ID();
        $day = explode('_', $shortcode_tag);
        $day = $day[0] ?? '';
        $all_working_hours = get_field('working_hours', $post_ID);
        if(!$all_working_hours) {
            return '';
        }

        if($day === 'lunchtime') {
            $working_hours = $all_working_hours['launch_time'];
            $working_hours_text = $working_hours['starts'] . $time_separator . $working_hours['ends'];
        } else {
            $working_hours = $all_working_hours[$day];
            $working_hours_text = $working_hours['opens'] . $time_separator . $working_hours['closes'];
        }

        if($working_hours_text === $time_separator) {
            $working_hours_text = __('Closed', THEME_NAME);
        }

        return $working_hours_text;
    }
    public function special_opening_hours()
    {
        $post_ID = get_the_ID();
        $all_working_hours = get_field('working_hours', $post_ID) ?? null;
        if(!$all_working_hours) {
            return '';
        }
        $working_hours = $all_working_hours['special_opening_hours'];
        $starting = strtotime($all_working_hours['special_opening_hours_start']);
        $ending = strtotime($all_working_hours['special_opening_hours_end'] . ' +24 hours');
        $current_date = time();
        if(($starting === false || $ending === false) || ($current_date >= $starting && $current_date <= $ending)) {
            return $working_hours;
        }
    }

    public function seopress_titles_variables_array($array) {
        $array[] = '[city_name]';
        $array[] = '[city_workshop_count]';
        $array[] = '[service_name]';
        $array[] = '[service_name_lowercase]';
        $array[] = '[service_workshop_count]';
        $array[] = '[workshop_name]';
        $array[] = '[workshop_street]';
        $array[] = '[workshop_zipcode]';
        $array[] = '[workshop_city]';
        $array[] = '[workshop_phone]';
        $array[] = '[workshop_email]';
        $array[] = '[workshop_description]';
        $array[] = '[total_workshop_count]';
        return $array;
    }

    public function seopress_titles_variables_replace_array($array) {
        $array[] = esc_attr(wp_strip_all_tags($this->city_name()));
        $array[] = esc_attr(wp_strip_all_tags($this->city_workshop_count()));
        $array[] = esc_attr(wp_strip_all_tags($this->service_name()));
        $array[] = esc_attr(wp_strip_all_tags($this->service_name_lowercase()));
        $array[] = esc_attr(wp_strip_all_tags($this->service_workshop_count()));
        $array[] = esc_attr(wp_strip_all_tags($this->workshop_name()));
        $array[] = esc_attr(wp_strip_all_tags($this->workshop_street()));
        $array[] = esc_attr(wp_strip_all_tags($this->workshop_zipcode()));
        $array[] = esc_attr(wp_strip_all_tags($this->workshop_city()));
        $array[] = esc_attr(wp_strip_all_tags($this->workshop_phone()));
        $array[] = esc_attr(wp_strip_all_tags($this->workshop_email()));
        $array[] = esc_attr(wp_strip_all_tags($this->workshop_description()));
        $array[] = esc_attr(wp_strip_all_tags($this->total_workshop_count()));
        return $array;
    }
}
