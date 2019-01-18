<?php
/**
 * Plugin Name: Custom Post Type Plugin
 * Plugin URI: https://github.com/cornbreadheadman/WP-Custom-Post-Type-Div
 * Description: Creates new custom post type CHICKENS and CHICKEN taxonomy TYPES and TAGS.
 * Version: 1.0
 * Author: Cornbread
 * Author URI: http://cornbread.me
 **/

// Create a new category called 'Type' for our CHICKENS.
if( ! function_exists('dp_define_CHICKEN_type_taxonomy')) :
    function dp_define_CHICKEN_type_taxonomy()
    {
        $labels = array( // these labels change the text in the WordPress dashboard to match your taxonomy name
            'name' => 'Types',
            'singular_name' => 'Type',
            'search_items'  => 'Search Types',
            'all_items'     => 'All Types',
            'parent_item'   => 'Parent Type:',
            'edit_item'     => 'Edit Type:',
            'update_item'   => 'Update Type',
            'add_new_item'  => 'Add New Type',
            'new_item_name' => 'New Type Name',
            'menu_name'     => 'Types',
            'view_item'     => 'View Types'
        );

        $args = array(
            'labels'       => $labels, //reference to the labels array above
            'hierarchical' => true, // whether a new instance of this taxonomy can have a parent
            'query_var'    => true // whether you can use query variables in the URL to access the new post types
        );

        // Tell WordPress about our new taxonomy and assign it to a post type
        register_taxonomy( 'type', 'CHICKENS', $args );
    }
endif;

// Call our new taxonomy function
add_action('init', 'dp_define_CHICKEN_type_taxonomy');

// Add tags to our CHICKENS
if( ! function_exists('dp_define_CHICKEN_tag_taxonomy')) :
    function dp_define_CHICKEN_tag_taxonomy()
    {
        $labels = array( // these labels change the text in the WordPress dashboard to match your taxonomy name
            'name'          => 'Tags',
            'singular_name' => 'Tag',
            'search_items'  => 'Search Tags',
            'popular_items' => ( 'Popular Tags' ),
            'parent_item'                => null,
            'parent_item_colon'          => null,
            'all_items'     => 'All Tags',
            'edit_item'     => 'Edit Tag:',
            'update_item'   => 'Update Tag',
            'add_new_item'  => 'Add New Tag',
            'new_item_name' => 'New Tag Name',
            'menu_name'     => 'Tags',
            'view_item'     => 'View Tags'
        );

        $args = array(
            'labels'       => $labels, //reference to the labels array above
            'hierarchical' => false, // whether a new instance of this taxonomy can have a parent
            'query_var'    => true // whether you can use query variables in the URL to access the new post types
        );

        // Tell WordPress about our new taxonomy and assign it to a post type
        register_taxonomy( 'tag', 'CHICKENS', $args );

}
endif;

// Call our new taxonomy function
add_action('init', 'dp_define_CHICKEN_tag_taxonomy');

// Create our new post type
if( ! function_exists('dp_register_CHICKENS')) :
    function dp_register_CHICKENS()
    {
        $labels = array( // these labels change the text in the WordPress dashboard and other places to match your custom post type name
            'name'               => 'CHICKENS',
            'singular_name'      => 'CHICKEN',
            'add_new'            => 'Add New CHICKEN',
            'add_new_item'       => 'Add New CHICKEN',
            'edit_item'          => 'Edit CHICKEN',
            'new item'           => 'New CHICKEN',
            'all_items'          => 'All CHICKENS',
            'view_item'          => 'View CHICKEN',
            'search_items'       => 'Search CHICKENS',
            'not_found'          => 'No CHICKENS found',
            'not_found_in_trash' => 'No CHICKENS found in Trash',
            'menu_name'          => 'CHICKENS'
        );

        $args = array(
            'labels'      => $labels, // reference to the labels array above
            'public'      => true, // whether the post type is available in the admin dashboard or front-end of site
            'taxonomies'  => array( 'Type', 'Tag'), // currently set to the Type taxonomy we created in the function above. You could leave blank for none or
            'rewrite'     => array( 'slug' => 'CHICKEN'), // base URL to use for your post type
            'hierarchical'=> false, // whether a new instance of this post type can have a parent. 'page-attributes' must be added to the supports array below for this to work.
            'has_archive' => true, // enables archive page for post type. Copy page template from theme and rename archive-CHICKENS.php
            'supports'    => array( // this array defines what meta boxes appear when adding/editing the post type
                'title',
                'editor',
                'thumbnail',
                'custom-fields',
                'comments',
                'excerpt',
                'revisions'
            ),
            'menu_icon' => 'dashicons-edit', // sets the icon to display in the menu
            'menu_position' => 5, // position in the menu; the higher the number, the lower the position
        );

        // Tell WordPress about our new post type
        register_post_type( 'CHICKENS', $args );

    }

endif;

// Call our new post type function
add_action( 'init', 'dp_register_CHICKENS' );

// Add the Divi Page Builder to the new post type
function my_et_builder_post_types( $post_types ) {
    $post_types[] = 'CHICKENS';

    return $post_types;
}
add_filter( 'et_builder_post_types', 'my_et_builder_post_types' );

/*
 * REGISTER STYLES
 * This function and following action can be removed if you copy the single-project.php file from
 * the parent Divi folder, place it in your child-theme folder, and rename it single-CHICKENS.php
 * (or single-[INSERT YOUR POST TYPE].PHP)
 */
function register_dp_cpt_plugin_styles() {

    wp_register_style('dp-cpt-plugin-css', plugin_dir_url( __FILE__ ) . 'css/style.css', false, '1.0.0' );
    wp_enqueue_style( 'dp-cpt-plugin-css' );

}

add_action( 'wp_enqueue_scripts', 'register_dp_cpt_plugin_styles' );

// This is required for pretty links to custom post types to work
function dp_CHICKEN_cpt_install() {

    dp_define_CHICKEN_type_taxonomy();
    dp_define_CHICKEN_tag_taxonomy();
    dp_register_CHICKENS();
    flush_rewrite_rules();

}

// When the plugin is deactivated/activated, run the pretty link function above
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
register_activation_hook( __FILE__, 'dp_CHICKEN_cpt_install' );
