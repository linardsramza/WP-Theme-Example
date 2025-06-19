<?php

/**
 * Class responsible for most hooks.
 */
class Custom_Hooks
{
    public function __construct()
    {
        add_filter('body_class', [$this, 'body_classes']);
        add_filter('seopress_titles_custom_tax', [$this, 'sp_titles_custom_tax'], 10, 2);
        add_filter('post_type_link', [$this, 'cpts_permalink_structure'], 10, 4);
        add_action('parse_request', [$this, 'post_type_slug_rewrite'], 10, 1);
        add_action('template_redirect', [$this, 'workshops_and_cities_redirects']);
        add_filter('gform_field_content', [$this, 'remove_textarea_sizes'], 10, 2);
        add_filter('seopress_pro_breadcrumbs_crumbs', [$this, 'theme_cpt_breadcrumbs']);
        add_filter('rest_cities_query', [$this, 'cities_parent_page_list']);
        add_action('post_updated', [$this, 'update_workshop_info'], 10, 3);
        add_filter('the_content', [$this, 'remove_double_https']);
        add_filter('wp_kses_allowed_html', [$this, 'additional_tags_in_editor'], 10, 2);
        add_filter('acf/pre_save_block', [$this, 'set_block_name_attribute']);

        // Auto populated select gform fields
        add_filter('gform_pre_render', [$this, 'populate_workshops']);
        add_filter('gform_pre_validation', [$this, 'populate_workshops']);
        add_filter('gform_pre_submission', [$this, 'populate_workshops']);
        add_filter('gform_pre_submission_filter', [$this, 'populate_workshops']);
        add_filter('gform_admin_pre_render', [$this, 'populate_workshops']);
    }

    public function body_classes($classes)
    {
        $classes[] = 'frontend';
        return $classes;
    }

    // If taxonomy with multiple terms are used in SEOPress title tag or meta description, then return the list with terms seperated with commas
    public function sp_titles_custom_tax($terms, $tax)
    {
        global $post;

        //Get all associated post terms
        $terms = strip_tags(get_the_term_list($post->ID, $tax, '', ', ', ''));

        //Check for errors
        if (!is_wp_error($terms)) {
            return $terms;
        }
    }

    public function cpts_permalink_structure($post_link, $post, $leavename, $sample)
    {
        if (strpos($post_link, '%workshops-cities%') == !false) {
            $projectscategory_type_term = get_the_terms($post->ID, 'workshops-cities');
            if (!empty($projectscategory_type_term))
                $post_link = str_replace('%workshops-cities%', array_pop($projectscategory_type_term)->
                slug, $post_link);
            else
                $post_link = str_replace('%workshops-cities%', 'uncategorized', $post_link);
        }

        if (strpos($post_link, '%workshops-regions%') == !false) {
            $projectscategory_type_term = get_the_terms($post->ID, 'workshops-regions');
            if (!empty($projectscategory_type_term))
                $post_link = str_replace('%workshops-regions%', array_pop($projectscategory_type_term)->
                slug, $post_link);
            else
                $post_link = str_replace('%workshops-regions%', 'uncategorized', $post_link);
        }

        if (strpos($post_link, '%warehouses-cities%') == !false) {
            $projectscategory_type_term = get_the_terms($post->ID, 'warehouses-cities');
            if (!empty($projectscategory_type_term))
                $post_link = str_replace('%warehouses-cities%', array_pop($projectscategory_type_term)->
                slug, $post_link);
            else
                $post_link = str_replace('%warehouses-cities%', 'uncategorized', $post_link);
        }

        if (strpos($post_link, '%warehouses-regions%') == !false) {
            $projectscategory_type_term = get_the_terms($post->ID, 'warehouses-regions');
            if (!empty($projectscategory_type_term))
                $post_link = str_replace('%warehouses-regions%', array_pop($projectscategory_type_term)->
                slug, $post_link);
            else
                $post_link = str_replace('%warehouses-regions%', 'uncategorized', $post_link);
        }

        return $post_link;
    }

    public function post_type_slug_rewrite($query)
    {

        $post_types = ['cities', 'workshops'];

        // If to make sure that code runs only for allowed post types and not in admin area
        $current_post_type = $query->query_vars['post_type'] ?? '';

        if (count($query->query_vars) > 0 && in_array($current_post_type, $post_types) && !is_admin()) {
            $slug = basename($query->request);

            $requested_post = get_posts([
                'name' => $slug,
                'post_type' => $post_types,
            ]);

            if (count($requested_post) < 1) {
                return $query;
            }

            $post_type = $requested_post[0]->post_type;
            $post_id = $requested_post[0]->ID;

            // If multiple posts have same slug, set correct post type
            if (count($requested_post) > 1) {
                foreach ($requested_post as $post) {
                    $p_id = $post->ID;
                    $permalink = get_permalink($p_id);
                    $current_link = get_home_url() . '/' . $query->request;
                    if ($permalink === $current_link) {
                        $post_type = $post->post_type;
                        $post_id = $post_id;
                    }
                }
            }

            // Change post slug if post has parent posts
            $parents = get_post_ancestors($post_id);
            $full_slug = $slug;
            $full_link = get_permalink($post_id);

            if ($parents) {
                $full_slug = '';
                foreach ($parents as $parent) {
                    $full_slug .= get_post_field('post_name', $parent) . '/';
                }
                $full_slug .= $slug;
            }

            $request_has_correct_slug = strpos($full_link, $query->request);

            // If no post was found, return basic query, if post/posts were found update query to correct post
            if ($request_has_correct_slug === false) {
                return $query;
            } else {
                $updated_query = array(
                    'page' => '',
                    'post_type' => $post_type,
                    'name' => $slug,
                    $post_type => $full_slug
                );

                $query->query_vars = $updated_query;

                return $query;
            }

        } else {
            return $query;
        }
    }

    // Workshop and city redirects

    function workshops_and_cities_redirects()
    {
        if (is_404()) {
            $post_types = ['cities', 'workshops'];
            global $wp_query;
            $current_post_type = $wp_query->query['post_type'] ?? '';
            if (!in_array($current_post_type, $post_types)) {
                return;
            }

            $requested_url_path = explode('/', $wp_query->query['name'])[0];
            $requested_post = get_posts([
                'name' => $requested_url_path,
                'post_type' => ['cities'],
            ]);

            $redirect_link = get_field('cities_redirect_page', 'options');
            if (count($requested_post) > 0) {
                $redirect_link = get_the_permalink($requested_post[0]->ID);
            }

            wp_redirect($redirect_link, 301);
            exit;
        }
    }

    // Remove gravity forms textarea rows and cols
    public function remove_textarea_sizes($field_content, $field)
    {
        if ($field->type == 'textarea') {
            return str_replace("rows='10' cols='50'", "", $field_content);
        }
        return $field_content;
    }

    // Filter breadcrumbs
    public function theme_cpt_breadcrumbs($crumbs)
    {

        global $post;

        // Breadcrumb structure for workshops cpt
        if (is_singular("workshops")):
            $workshops_slug = get_field("workshops_link_structure", "options");

            if (!empty($workshops_slug) || !is_null($workshops_slug)) :
                $slug_arr = explode("/", $workshops_slug);
                if ($slug_arr) :
                    $slug_arr = array_reverse($slug_arr);
                    foreach ($slug_arr as $item):
                        $str = str_replace("%", "", $item);
                        $term = get_the_terms($post->ID, $str);
                        $page = get_page_by_path($str);
                        $new_crumb = [];

                        if ($term && !is_wp_error($term)) :
                            $term_page = get_posts(array(
                                'name' => $term[0]->slug,
                                'post_type' => 'cities',
                                'post_status' => 'publish',
                                'numberposts' => 1
                            ));

                            if (isset($term_page[0])) :
                                $new_crumb[] = [
                                    0 => $term_page[0]->post_title,
                                    1 => get_the_permalink($term_page[0]->ID)
                                ];
                            endif;
                        elseif ($page):
                            $new_crumb[] = [
                                0 => $page->post_title,
                                1 => get_the_permalink($page->ID)
                            ];
                        endif;

                        array_splice($crumbs, 1, 0, $new_crumb);
                    endforeach;
                endif;
            endif;
        endif;

        // Breadcrumb structure for cities cpt
        if (is_singular("cities")):
            $cities_slug = get_field("city_link_structure", "options");

            if (!empty($cities_slug) || !is_null($cities_slug)) :
                $page = get_page_by_path($cities_slug);

                if ($page):
                    $new_crumb = [];
                    $new_crumb[] = [
                        0 => $page->post_title,
                        1 => get_the_permalink($page->ID)
                    ];
                endif;

                array_splice($crumbs, 1, 0, $new_crumb);
            endif;
        endif;

        // Breadcrumb structure for warehouses cpt
        if (is_singular("warehouses")):
            $warehouse_slug = get_field("warehouses_link_structure", "options") ?? '';

            if (!empty($warehouse_slug) || !is_null($warehouse_slug)) :
                $slug_arr = explode("/", $warehouse_slug);
                if ($slug_arr) :
                    $slug_arr = array_reverse($slug_arr);
                    foreach ($slug_arr as $item):
                        $str = str_replace("%", "", $item);
                        $term = get_the_terms($post->ID, $str);
                        $page = get_page_by_path($str);

                        if ($term && !is_wp_error($term)) :
                            $term_page = get_posts(array(
                                'name' => $term[0]->slug,
                                'post_type' => 'cities',
                                'post_status' => 'publish',
                                'numberposts' => 1
                            ));

                            if ($term_page[0]) :
                                $new_crumb = [];
                                $new_crumb[] = [
                                    0 => $term_page[0]->post_title,
                                    1 => get_the_permalink($term_page[0]->ID)
                                ];
                            endif;
                        elseif ($page):
                            $new_crumb = [];
                            $new_crumb[] = [
                                0 => $page->post_title,
                                1 => get_the_permalink($page->ID)
                            ];
                        endif;

                        array_splice($crumbs, 1, 0, $new_crumb);
                    endforeach;
                endif;
            endif;
        endif;

        return $crumbs;
    }

    // Remove cities services pages from parent page list
    public function cities_parent_page_list($params)
    {
        $params['post_parent'] = 0;
        return $params;
    }

    public function update_workshop_info($post_ID)
    {
        if (get_post_type($post_ID) !== 'workshops') {
            return;
        }

        // Booking link
        $booking_link = Helper::get_booking_link($post_ID);

        // Get values
        $address = get_field('workshop_address', $post_ID);
        $contact_info = get_field('contact_info', $post_ID);
        $services_terms = get_the_terms($post_ID, 'services');
        $services = [];
        $services_ids = [];
        if ($services_terms) {
            foreach ($services_terms as $services_term) {
                $services[] = $services_term->name;
                $services_ids[] = $services_term->term_id;
            }
        }
        $city = get_the_terms($post_ID, 'workshops-cities');
        $city_name = $city[0]->name ?? '';
        $city_id = $city[0]->term_id ?? 0;
        $certificate_terms = get_the_terms($post_ID, 'workshops-certificates');
        $certificates_ids = [];
        if ($certificate_terms) {
            foreach ($certificate_terms as $certificate_term) {
                $certificates_ids[] = $certificate_term->term_id;
            }
        }
        $working_hours_data = get_field('working_hours', $post_ID);
        $working_hours = [];
        $open_evening = false;
        $open_weekends = false;
        $special_opening_hours = [];
        if ($working_hours_data) {
            $working_hours = [
                $working_hours_data['monday'],
                $working_hours_data['tuesday'],
                $working_hours_data['wednesday'],
                $working_hours_data['thursday'],
                $working_hours_data['friday'],
                $working_hours_data['saturday'],
                $working_hours_data['sunday'],
            ];

            // Check if at least one evening workshop is opened passed 18
            $closing_time = array_column($working_hours, 'closes');
            foreach ($closing_time as $ct) {
                $close_time = strtotime($ct . ':00');
                $evening_time = strtotime('18:00:00');
                if (!$close_time) {
                    continue;
                }
                if ($close_time > $evening_time) {
                    $open_evening = true;
                }
            }

            // Check if workshop works on Saturday or Sunday
            if ((!in_array('-', $working_hours_data['saturday']) && !in_array('', $working_hours_data['saturday'])) ||
                (!in_array('-', $working_hours_data['sunday']) && !in_array('', $working_hours_data['sunday']))) {
                $open_weekends = true;
            }

            // Special opening hours
            $special_opening_hours = [
                'text' => $working_hours_data['special_opening_hours'] ?? '',
                'starts' => $working_hours_data['special_opening_hours_start'] ?? '',
                'ends' => $working_hours_data['special_opening_hours_end'] ?? '',
            ];
        }

        // Set values
        $workshop_info = [
            'booking_link' => $booking_link,
            'city' => $city_name,
            'city_id' => $city_id,
            'address_line_1' => $address['address'],
            'address_line_2' => $address['zip_code'] . ' ' . $address['city'],
            'longitude' => $address['longitude'],
            'latitude' => $address['latitude'],
            'phone' => $contact_info['phone_number'] ?? '',
            'email' => $contact_info['email'] ?? '',
            'rating' => get_field('average_rating', $post_ID) ?? 0,
            'hide_rating' => get_field('hide_rating', $post_ID) ?? false,
            'services' => $services,
            'services_ids' => $services_ids,
            'certifications' => $certificates_ids,
            'working_hours' => [$working_hours],
            'open_evening' => $open_evening,
            'open_weekends' => $open_weekends,
            'special_opening_hours' => $special_opening_hours,
        ];

        update_post_meta($post_ID, 'theme_workshop_details', $workshop_info);
    }

    public function additional_tags_in_editor($allowedposttags)
    {
        $allowedposttags['iframe'] = [
            'align' => true,
            'allow' => true,
            'allowfullscreen' => true,
            'class' => true,
            'frameborder' => true,
            'height' => true,
            'id' => true,
            'name' => true,
            'scrolling' => true,
            'src' => true,
            'style' => true,
            'width' => true,
            'mozallowfullscreen' => true,
            'title' => true,
        ];

        $allowedposttags['script'] = [
            'async' => true,
            'crossorigin' => true,
            'defer' => true,
            'integrity' => true,
            'nomodule' => true,
            'referrerpolicy' => true,
            'src' => true,
            'type' => true,
            'charset' => true,
            'data-domain-script' => true
        ];

        $allowedposttags['embed'] = [
            'align' => true,
            'allow' => true,
            'allowfullscreen' => true,
            'class' => true,
            'frameborder' => true,
            'height' => true,
            'id' => true,
            'name' => true,
            'scrolling' => true,
            'src' => true,
            'style' => true,
            'width' => true,
            'mozallowfullscreen' => true,
            'title' => true,
        ];

        return $allowedposttags;
    }

    public function set_block_name_attribute($attributes)
    {
        if (empty($attributes['theme_block_name'])) {
            $attributes['theme_block_name'] = 'theme-block-name-' . uniqid() . '-' . time();
        }
        return $attributes;
    }

    public function populate_workshops($form)
    {

        foreach ($form['fields'] as $field) {

            if ($field['type'] !== 'select' || $field['cssClass'] !== 'select-workshop') {
                continue;
            }

            // Get all workshops
            $workshops = get_posts([
                'post_type' => 'workshops',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'orderby' => 'title',
                'order' => 'ASC'
            ]);

            foreach ($workshops as $workshop) {
                $choices[] = array('text' => $workshop->post_title, 'value' => $workshop->post_title);
            }

            $field['placeholder'] = __('Select a workshop', THEME_NAME);
            $field['choices'] = $choices;
        }

        return $form;
    }

    public function remove_double_https($content)
    {
        // Remove duplicated https/http
        $pattern = '/(https?:\/\/)(https?:\/\/)?/i';
        $content = preg_replace($pattern, '$1', $content);

        // Remove duplicated https/http from shortcodes
        $pattern = '/(https?:\/\/)\[(.*?)\]|\b(http?:\/\/)\[(.*?)\]/i';
        $content = preg_replace($pattern, '[$2$4]', $content);

        return $content;
    }
}

new Custom_Hooks();