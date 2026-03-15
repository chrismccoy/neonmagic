<?php
/**
 * NeonMagic AJAX Handlers
 *
 * @package NeonMagic
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Load More Posts
function neonmagic_load_more() {
    check_ajax_referer( 'neonmagic_nonce', 'nonce' );

    $page     = isset( $_POST['page'] )     ? absint( $_POST['page'] )     : 1;
    $cat_id   = isset( $_POST['cat_id'] )   ? absint( $_POST['cat_id'] )   : 0;
    $tag_slug = isset( $_POST['tag_slug'] ) ? sanitize_text_field( $_POST['tag_slug'] ) : '';
    $search   = isset( $_POST['search'] )   ? sanitize_text_field( $_POST['search'] )   : '';

    $args = [
        'post_type'      => 'post',
        'posts_per_page' => (int) get_option( 'posts_per_page', 12 ),
        'paged'          => $page,
        'post_status'    => 'publish',
    ];

    if ( $cat_id ) {
        $args['cat'] = $cat_id;
    }
    if ( $tag_slug ) {
        $args['tag'] = $tag_slug;
    }
    if ( $search ) {
        $args['s'] = $search;
    }

    $query = new WP_Query( $args );

    if ( ! $query->have_posts() ) {
        wp_send_json_success( [ 'html' => '', 'max_pages' => 0 ] );
        return;
    }

    ob_start();
    while ( $query->have_posts() ) {
        $query->the_post();
        get_template_part( 'template-parts/content', 'card' );
    }
    wp_reset_postdata();
    $html = ob_get_clean();

    wp_send_json_success( [
        'html'      => $html,
        'max_pages' => (int) $query->max_num_pages,
    ] );
}
add_action( 'wp_ajax_neonmagic_load_more',        'neonmagic_load_more' );
add_action( 'wp_ajax_nopriv_neonmagic_load_more', 'neonmagic_load_more' );

// Track Downloads
function neonmagic_track_download() {
    check_ajax_referer( 'neonmagic_nonce', 'nonce' );

    $post_id = isset( $_POST['post_id'] ) ? absint( $_POST['post_id'] ) : 0;

    if ( ! $post_id || ! get_post( $post_id ) ) {
        wp_send_json_error( [ 'message' => 'Invalid post.' ] );
        return;
    }

    // Only track free items
    if ( nm_is_paid( $post_id ) ) {
        wp_send_json_error( [ 'message' => 'Paid item — no tracking.' ] );
        return;
    }

    $new_count    = nm_increment_download_count( $post_id );
    $download_url = nm_get_download_url( $post_id );

    wp_send_json_success( [
        'count'        => $new_count,
        'download_url' => $download_url,
    ] );
}
add_action( 'wp_ajax_neonmagic_track_download',        'neonmagic_track_download' );
add_action( 'wp_ajax_nopriv_neonmagic_track_download', 'neonmagic_track_download' );
