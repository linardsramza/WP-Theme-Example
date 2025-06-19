<?php

class Register_CPTs
{
    public function __construct()
    {
        add_action('init', [$this, 'register_news_cpt']);
        add_action('init', [$this, 'register_cities_cpt']);
        add_action('init', [$this, 'register_workshops_cpt']);
        add_action('init', [$this, 'register_warehouses_cpt']);
        add_action('init', [$this, 'register_templates_cpt']);
    }

    public function register_news_cpt() {
        $news_slug = get_field('news_slug', 'options');
        if(empty($news_slug) || is_null($news_slug)) {
            $news_slug = __( 'news', THEME_NAME );
        }

        $news_label = get_field('news_label', 'options');
        if(empty($news_label) || is_null($news_label)) {
            $news_label = __( 'News', THEME_NAME );
        }

        $args = array(
            'public'             => true,
            'labels'             => array(
                'name'          => $news_label,
                'singular_name' => $news_label,
            ),
            'menu_icon'          => 'dashicons-admin-post',
            'supports'           => array( 'title', 'thumbnail', 'editor', 'excerpt' ),
            'has_archive'        => true,
            'publicly_queryable' => true,
            'show_in_rest'       => true,
            'rewrite'            => [
                'slug'       => $news_slug,
                'with_front' => false
            ],
        );

        register_post_type( 'news', $args );
    }

    public function register_workshops_cpt() {
        $workshops_slug = get_field('workshops_link_structure', 'options');
        if(empty($workshops_slug) || is_null($workshops_slug)) {
            $workshops_slug = __( 'workshops', THEME_NAME );
        }

        $args = array(
            'public'             => true,
            'labels'             => array(
                'name'          => __( 'Workshops', THEME_NAME ),
                'singular_name' => __( 'Workshop', THEME_NAME ),
            ),
            'menu_icon'          => 'dashicons-admin-tools',
            'supports'           => array( 'title', 'editor', 'thumbnail' ),
            'has_archive'        => false,
            'publicly_queryable' => true,
            'show_in_rest'       => true,
            'rewrite'            => [
                'slug'       => $workshops_slug,
                'with_front' => false
            ],
        );

        register_post_type( 'workshops', $args );
    }

    public function register_warehouses_cpt() {
        $args = array(
            'public'             => true,
            'labels'             => array(
                'name'          => __( 'Warehouses', THEME_NAME ),
                'singular_name' => __( 'Warehouse', THEME_NAME ),
            ),
            'menu_icon'          => 'dashicons-admin-multisite',
            'supports'           => array( 'title', 'thumbnail' ),
            'has_archive'        => false,
            'publicly_queryable' => false,
            'show_in_rest'       => true,
            'rewrite'            => [
                'slug'       => 'warehouse',
                'with_front' => false
            ],
        );

        register_post_type( 'warehouses', $args );
    }

    public function register_cities_cpt()
    {
        $city_slug = get_field('city_link_structure', 'options');
        if(empty($city_slug) || is_null($city_slug)) {
            $city_slug = __( 'cities', THEME_NAME );
        }
        $args = array(
            'public'             => true,
            'labels'             => array(
                'name'          => __( 'Cities', THEME_NAME ),
                'singular_name' => __( 'City', THEME_NAME ),
            ),
            'menu_icon'          => 'dashicons-location-alt',
            'supports'           => array( 'title', 'editor', 'thumbnail', 'page-attributes' ),
            'hierarchical'       => true,
            'has_archive'        => false,
            'publicly_queryable' => true,
            'show_in_rest'       => true,
            'rewrite'            => [
                'slug'       => $city_slug,
                'with_front' => false
            ],
            'menu_position'      => 29,
        );

        register_post_type( 'cities', $args );
    }

    public function register_templates_cpt()
    {
        $args = array(
            'public'             => true,
            'labels'             => array(
                'name'          => __( 'Templates', THEME_NAME ),
                'singular_name' => __( 'Template', THEME_NAME ),
            ),
            'menu_icon'          => 'dashicons-editor-table',
            'supports'           => array( 'title', 'editor' ),
            'has_archive'        => false,
            'publicly_queryable' => false,
            'show_in_rest'       => true,
            'rewrite'            => [
                'slug'       => __( 'templates', THEME_NAME ),
                'with_front' => false
            ],
        );

        register_post_type( 'cpt-templates', $args );
    }
}
