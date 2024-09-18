<?php 

class Register_Taxonomies
{
    public function __construct()
    {
        add_action( 'init', [$this, 'register_services_taxonomy'] );
        add_action( 'init', [$this, 'register_news_taxonomy'] );
		add_action( 'init', [$this, 'register_workshops_city_taxonomy'] );
		add_action( 'init', [$this, 'register_warehouses_city_taxonomy'] );
        add_action( 'init', [$this, 'register_workshops_region_taxonomy'] );
        add_action( 'init', [$this, 'register_warehouses_region_taxonomy'] );
        add_action( 'init', [$this, 'register_workshops_certificate_taxonomy'] );
    }

    public function register_news_taxonomy()
    {
        $categories_tax = array(
            'labels'             => array(
                'name'           => __( 'News categories', THEME_NAME ),
                'singular_name'  => __( 'News category', THEME_NAME ),
                'new_item_name'  => __( 'New category', THEME_NAME ),
            ),
            'hierarchical'               => true,
            'public'                     => false,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => false,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
        );
        register_taxonomy('news-categories', ['news'], $categories_tax);
    }

    public function register_services_taxonomy()
    {
        $service_tax = array(
            'labels'             => array(
                'name'           => __( 'Services', THEME_NAME ),
                'singular_name'  => __( 'Service', THEME_NAME ),
                'new_item_name'  => __( 'New service', THEME_NAME ),
            ),
            'hierarchical'      => false,
            'public'            => false,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'service' ),
            'show_in_rest'      => true,
        );
        register_taxonomy('services', ['workshops'], $service_tax);
    }

    public function register_workshops_city_taxonomy()
    {
        $city_tax = array(
            'labels'             => array(
                'name'           => __( 'Cities', THEME_NAME ),
                'singular_name'  => __( 'City', THEME_NAME ),
                'new_item_name'  => __( 'New city', THEME_NAME ),
            ),
            'hierarchical'               => false,
            'public'                     => false,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => false,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
        );
        register_taxonomy('workshops-cities', ['workshops'], $city_tax);
    }

    public function register_warehouses_city_taxonomy()
    {
        $city_tax = array(
            'labels'             => array(
                'name'           => __( 'Cities', THEME_NAME ),
                'singular_name'  => __( 'City', THEME_NAME ),
                'new_item_name'  => __( 'New city', THEME_NAME ),
            ),
            'hierarchical'               => false,
            'public'                     => false,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => false,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
        );
        register_taxonomy('warehouses-cities', ['warehouses'], $city_tax);
    }

    public function register_workshops_region_taxonomy()
    {
        $region_tax = array(
            'labels'             => array(
                'name'           => __( 'Regions', THEME_NAME ),
                'singular_name'  => __( 'Region', THEME_NAME ),
                'new_item_name'  => __( 'New Region', THEME_NAME ),
            ),
            'hierarchical'               => false,
            'public'                     => false,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => false,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
        );
        register_taxonomy('workshops-regions', ['workshops'], $region_tax);
    }

    public function register_warehouses_region_taxonomy()
    {
        $region_tax = array(
            'labels'             => array(
                'name'           => __( 'Regions', THEME_NAME ),
                'singular_name'  => __( 'Region', THEME_NAME ),
                'new_item_name'  => __( 'New Region', THEME_NAME ),
            ),
            'hierarchical'               => false,
            'public'                     => false,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => false,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
        );
        register_taxonomy('warehouses-regions', ['warehouses'], $region_tax);
    }

    public function register_workshops_certificate_taxonomy()
    {
        $region_tax = array(
            'labels'             => array(
                'name'           => __( 'Certificates', THEME_NAME ),
                'singular_name'  => __( 'Certificate', THEME_NAME ),
                'new_item_name'  => __( 'New Certificate', THEME_NAME ),
            ),
            'hierarchical'               => false,
            'public'                     => false,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => false,
            'show_tagcloud'              => true,
            'show_in_rest'               => true,
        );
        register_taxonomy('workshops-certificates', ['workshops'], $region_tax);
    }
}