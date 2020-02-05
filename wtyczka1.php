<?php
/*
Plugin Name: Dodaje "Produkty"
Description: opis
Version: 1.0.0
Author: Kacper
Author URI: -
*/

// Utworzenie nowego typu "produkty:
function create_posttype() {
    register_post_type( 'Produkty',
        array(
            'labels' => array(
                'name' => __( 'Produkty' ),
                'singular_name' => __( 'Produkt' )
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'produkty'),
        )
    );
}
// dodanie "produkty" do panelu wordpressa
add_action( 'init', 'create_posttype' );

/*
* Dodatkowe opcje
*/
 
function custom_post_type() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Produkty', 'Post Type General Name', 'twentythirteen' ),
        'singular_name'       => _x( 'Produkt', 'Post Type Singular Name', 'twentythirteen' ),
        'menu_name'           => __( 'Produkty', 'twentythirteen' ),
        // Parent Movie == Obecny Produkt ??
        'parent_item_colon'   => __( 'Obecny Produkt', 'twentythirteen' ),
        'all_items'           => __( 'Wszystkie produkty', 'twentythirteen' ),
        'view_item'           => __( 'Zobacz Produkt', 'twentythirteen' ),
        'add_new_item'        => __( 'Dodaj nowy produkt', 'twentythirteen' ),
        'add_new'             => __( 'Dodaj nowy', 'twentythirteen' ),
        'edit_item'           => __( 'Edytuj Produkt', 'twentythirteen' ),
        'update_item'         => __( 'Zaktualizuj produkt', 'twentythirteen' ),
        'search_items'        => __( 'Wyszukaj Produkt', 'twentythirteen' ),
        'not_found'           => __( 'Nie znaleźono', 'twentythirteen' ),
        'not_found_in_trash'  => __( 'Nie znaleźono w koszu', 'twentythirteen' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'Produkty', 'twentythirteen' ),
        'description'         => __( 'Opis kategorii produkty', 'twentythirteen' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array( 'genres' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'page',
    );
     
    // Registering your Custom Post Type
    register_post_type( 'Produkty', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'custom_post_type', 0 );
?>
