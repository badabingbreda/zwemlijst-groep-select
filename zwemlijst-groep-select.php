<?php
/**
 * Zwemlijst groep select
 *
 * @package     Package
 * @author      Badabingbreda
 * @license     GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name: Zwemlijst groep select
 * Plugin URI:  https://www.badabing.nl
 * Description: Selecteer meerdere leerlingen uit de groep-taxonomy zodat je niet steeds individuele leerlingen hoeft toe te voegen
 * Version:     1.0.0
 * Author:      Badabingbreda
 * Author URI:  https://www.badabing.nl
 * Text Domain: textdomain
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

define( 'ZWEMLIJSTGROEP_VERSION' 	, '1.0.0' );
define( 'ZWEMLIJSTGROEP_DIR'			, plugin_dir_path( __FILE__ ) );
define( 'ZWEMLIJSTGROEP_FILE'		, __FILE__ );
define( 'ZWEMLIJSTGROEP_URL' 		, plugins_url( '/', __FILE__ ) );

// enqueue the script on the admin side, that will allow us to select group from a dropdown and add in bulk
add_action( 'admin_enqueue_scripts' , function() {

    wp_enqueue_script( 'zwemlijst-groep-select', ZWEMLIJSTGROEP_URL . 'js/zwemlijst-groep-select.js', array( 'jquery' ), ZWEMLIJSTGROEP_VERSION, true );
} );

// add ajax listeners
add_action( 'wp_ajax_get_groups' , 'zwemlijst_taxonomy_get_groups' );
add_action( 'wp_ajax_get_group_data' , 'zwemlijst_get_group_data' );

/**
 * Callback to get the groups
 * @return [type] [description]
 */
function zwemlijst_taxonomy_get_groups() {

    $page = filter_input( INPUT_GET, 'page' , FILTER_SANITIZE_NUMBER_INT );
    $q = filter_input( INPUT_GET, 'q' , FILTER_SANITIZE_STRING );

    $max_per_page = 10;

    $terms = get_terms( [ 'taxonomy' => 'groep' , 'hide_empty' => false ] );

    $row = array();

    foreach ( $terms as $term ) {

    		if ( $term->slug == 'gestopt' ) continue;

            $row[] = array( 'id' => $term->term_id , 'text' => $term->name . " (aantal: {$term->count})" );

    }

    // only return a select number of results
    $row = array_slice( $row , ( $page - 1 ) * $max_per_page , $max_per_page  );

    echo json_encode( array( 'results' => $row , 'pagination' => array( 'more' => ( sizeof( $terms ) > ( $page * $max_per_page ) ) ) ) );


    wp_die();
}

/**
 * Callback to get students that have a certain term applied
 * @return [type] [description]
 */
function zwemlijst_get_group_data() {

    $groupid = filter_input( INPUT_GET, 'groupid' , FILTER_SANITIZE_NUMBER_INT );

    $row = [];

    $args = array(

                    'post_type' => 'leerling',
                    'numberposts' => -1,
                    'orderby' => 'title',
                    'order' => 'ASC',
                    'tax_query' => array(
                        'relation' => 'AND',
                        'badjeclause' => array(
                            'taxonomy' => 'groep',
                            'field' => 'id',
                            'terms' => [ $groupid ],
                            'operator' => 'IN',
                        ),
                        'gestoptclause' => array(
                            'taxonomy' => 'groep',
                            'field' => 'slug',
                            'terms' => [ 'gestopt' ],
                            'operator' => 'NOT IN',
                        ),
                    )
                );

    $results = new WP_Query( $args );

    if ( $results->have_posts() ):
        foreach ( $results->posts as $post ) {

                $row[] = array( 'id' => $post->ID , 'text' => $post->post_title );

        }
    endif;

    echo json_encode( $row );
    wp_die();
}