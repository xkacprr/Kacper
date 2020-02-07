<?php
/*
Plugin Name: products
Description: Wtyczka dodajaca nowy post_type o nazwie "Produkty" i metabox "własny url"
Version: 1.0.2
Author: Kacper
Author URI: 
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
 
// Nazwanie elementow interfejsu
 // Zmien 'twentythirteen' na swoj domain text, ktory znajdziesz np. w pliku style.css
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
     
// Ustawienie pozostalych opcji
     
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
	'register_meta_box_cb' => 'wpt_add_Produkt_metaboxes',
    );
     
    // rejestracja typu postu
    register_post_type( 'Produkty', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'custom_post_type', 0 );





// ADD METABOX "Własny URL"

/**
 * Adds a metabox to the right side of the screen under the “Publish” box
 */
function wpt_add_Produkt_metaboxes() {
	add_meta_box(
		'wpt_Produkty_location',
		'Własny URL',
		'wpt_Produkty_location',
		'Produkty',
		'side',
		'default'
	);
}

/**
 * If you wanted to have two sets of metaboxes.
 */
function add_Produkty_metaboxes_v2() {

	add_meta_box(
		'wpt_Produkty_date',
		'Produkt Date',
		'wpt_Produkty_date',
		'Produkty',
		'side',
		'default'
	);

	add_meta_box(
		'wpt_Produkty_location',
		'Własny URL',
		'wpt_Produkty_location',
		'Produkty',
		'normal',
		'high'
	);

}

/**
 * Output the HTML for the metabox.
 */
function wpt_Produkty_location() {
	global $post;

	// Nonce field to validate form request came from current site
	wp_nonce_field( basename( __FILE__ ), 'Produkt_fields' );

	// Get the location data if it's already been entered
	$location = get_post_meta( $post->ID, 'location', true );

	// Output the field
	echo '<input type="text" name="location" value="' . esc_textarea( $location )  . '" class="widefat">';

}

/**
 * Save the metabox data
 */
function wpt_save_Produkty_meta( $post_id, $post ) {

	// Return if the user doesn't have edit permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return $post_id;
	}

	// Verify this came from the our screen and with proper authorization,
	// because save_post can be triggered at other times.
	if ( ! isset( $_POST['location'] ) || ! wp_verify_nonce( $_POST['Produkt_fields'], basename(__FILE__) ) ) {
		return $post_id;
	}

	// Now that we're authenticated, time to save the data.
	// This sanitizes the data from the field and saves it into an array $Produkty_meta.
	$Produkty_meta['location'] = esc_textarea( $_POST['location'] );

	// Cycle through the $Produkty_meta array.
	// Note, in this example we just have one item, but this is helpful if you have multiple.
	foreach ( $Produkty_meta as $key => $value ) :

		// Don't store custom data twice
		if ( 'revision' === $post->post_type ) {
			return;
		}

		if ( get_post_meta( $post_id, $key, false ) ) {
			// If the custom field already has a value, update it.
			update_post_meta( $post_id, $key, $value );
		} else {
			// If the custom field doesn't have a value, add it.
			add_post_meta( $post_id, $key, $value);
		}

		if ( ! $value ) {
			// Delete the meta key if there's no value
			delete_post_meta( $post_id, $key );
		}

	endforeach;

}
add_action( 'save_post', 'wpt_save_Produkty_meta', 1, 2 );


// DODAJE SHORTCODE

    add_shortcode( 'pozyczka', 'display_custom_post_type' );

    function display_custom_post_type(){
        $args = array(
            'post_type' => 'Produkty',
            'post_status' => 'publish'
        );

        $string = '';
        $query = new WP_Query( $args );
        if( $query->have_posts() ){
            $string .= '<ul>';
            while( $query->have_posts() ){
                $query->the_post();
                $string .= the_content();
            }
            $string .= '</ul>';
        }
        wp_reset_postdata();
        return $string;
    }
?>