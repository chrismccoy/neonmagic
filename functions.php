<?php
/**
 * NeonMagic Theme Functions
 *
 * @package NeonMagic
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'NEONMAGIC_VERSION', '1.0.0' );
define( 'NEONMAGIC_DIR', get_template_directory() );
define( 'NEONMAGIC_URI', get_template_directory_uri() );

require_once NEONMAGIC_DIR . '/inc/nav-walkers.php';
require_once NEONMAGIC_DIR . '/inc/meta-boxes.php';
require_once NEONMAGIC_DIR . '/inc/helpers.php';
require_once NEONMAGIC_DIR . '/inc/ajax.php';

// Theme Setup
function neonmagic_setup() {
    load_theme_textdomain( 'neonmagic', NEONMAGIC_DIR . '/languages' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'script', 'style',
    ] );
    add_theme_support( 'automatic-feed-links' );
    add_theme_support( 'customize-selective-refresh-widgets' );
    add_theme_support( 'responsive-embeds' );

    // Custom logo with specific dimensions to match the brutalist badge
    add_theme_support( 'custom-logo', [
        'height'      => 60,
        'width'       => 200,
        'flex-width'  => true,
        'flex-height' => true,
    ] );

    register_nav_menus( [
        'primary' => __( 'Primary Navigation', 'neonmagic' ),
        'footer'  => __( 'Footer Navigation', 'neonmagic' ),
    ] );

    // Set default thumbnail size matching the card aspect ratio (4:3)
    set_post_thumbnail_size( 800, 600, true );
    add_image_size( 'neonmagic-card',    400, 300, true );
    add_image_size( 'neonmagic-square',  600, 600, true );
    add_image_size( 'neonmagic-thumb',   150, 150, true );
    add_image_size( 'neonmagic-related', 400, 300, true );
}
add_action( 'after_setup_theme', 'neonmagic_setup' );

// Content Width
function neonmagic_content_width() {
    $GLOBALS['content_width'] = 1400;
}
add_action( 'after_setup_theme', 'neonmagic_content_width', 0 );

// Widget Areas
function neonmagic_widgets_init() {
    register_sidebar( [
        'name'          => __( 'Sidebar – File Types', 'neonmagic' ),
        'id'            => 'sidebar-1',
        'description'   => __( 'Add widgets here. Not shown by default (categories/recent/tags are rendered natively).', 'neonmagic' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s mb-8">',
        'after_widget'  => '</div>',
        'before_title'  => '<h3 class="font-sans font-black text-xl uppercase mb-4">',
        'after_title'   => '</h3>',
    ] );
}
add_action( 'widgets_init', 'neonmagic_widgets_init' );

// Scripts and Styles
function neonmagic_enqueue_scripts() {
    // Google Fonts
    wp_enqueue_style(
        'neonmagic-google-fonts',
        'https://fonts.googleapis.com/css2?family=IBM+Plex+Mono:wght@500;600;700&family=Inter:wght@800;900&display=swap',
        [],
        null
    );

    // Font Awesome 6
    wp_enqueue_style(
        'font-awesome',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css',
        [],
        '6.5.2'
    );

    // Theme stylesheet (variables + admin meta box styles)
    wp_enqueue_style(
        'neonmagic-style',
        get_stylesheet_uri(),
        [ 'neonmagic-google-fonts', 'font-awesome' ],
        NEONMAGIC_VERSION
    );

    // Theme JS
    wp_enqueue_script(
        'neonmagic-theme',
        NEONMAGIC_URI . '/assets/js/theme.js',
        [],
        NEONMAGIC_VERSION,
        true
    );

    // Pass AJAX URL and nonce to JS
    wp_localize_script( 'neonmagic-theme', 'NeonMagic', [
        'ajaxUrl'       => admin_url( 'admin-ajax.php' ),
        'nonce'         => wp_create_nonce( 'neonmagic_nonce' ),
        'homeUrl'       => home_url( '/' ),
        'loadMoreText'  => __( 'Load More Files', 'neonmagic' ),
        'loadingText'   => __( 'Loading...', 'neonmagic' ),
        'noMoreText'    => __( 'All Files Loaded', 'neonmagic' ),
        'copySuccess'   => __( 'Link copied!', 'neonmagic' ),
        'savedText'     => __( 'Saved!', 'neonmagic' ),
        'unsavedText'   => __( 'Save', 'neonmagic' ),
    ] );

    // Comment reply script
    if ( is_singular() && comments_open() ) {
        wp_enqueue_script( 'comment-reply' );
    }
}
add_action( 'wp_enqueue_scripts', 'neonmagic_enqueue_scripts' );

// Tailwind Config
function neonmagic_tailwind_head() {
    ?>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'pop-cyan':   '#5CE1E6',
                        'pop-yellow': '#FFDE59',
                        'pop-pink':   '#FF90E8',
                        'pop-white':  '#FFFFFF',
                        'pop-black':  '#000000',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['"IBM Plex Mono"', 'monospace'],
                    },
                    boxShadow: {
                        'hard-sm': '4px 4px 0 0 #000',
                        'hard-md': '8px 8px 0 0 #000',
                        'hard-lg': '10px 10px 0 0 #000',
                        'hard-xl': '16px 16px 0 0 #000',
                    },
                    borderWidth: { '3': '3px' },
                    animation: { 'marquee': 'marquee 15s linear infinite' },
                    keyframes: {
                        marquee: {
                            '0%':   { transform: 'translateX(0%)' },
                            '100%': { transform: 'translateX(-100%)' },
                        }
                    }
                }
            }
        }
    </script>
    <style type="text/tailwindcss">
        @layer utilities {
            .btn-physics {
                @apply transition-all duration-150 ease-out active:translate-x-0 active:translate-y-0 active:shadow-none hover:-translate-y-1 hover:-translate-x-1 hover:shadow-hard-md;
            }
        }
    </style>
    <?php
}
add_action( 'wp_head', 'neonmagic_tailwind_head', 1 );

// Admin Scripts
function neonmagic_admin_enqueue( $hook ) {
    if ( ! in_array( $hook, [ 'post.php', 'post-new.php' ], true ) ) {
        return;
    }
    wp_enqueue_media();
    wp_enqueue_style(
        'neonmagic-admin',
        get_stylesheet_uri(),
        [],
        NEONMAGIC_VERSION
    );
    wp_enqueue_script(
        'neonmagic-admin',
        NEONMAGIC_URI . '/assets/js/admin.js',
        [ 'jquery', 'media-upload' ],
        NEONMAGIC_VERSION,
        true
    );
}
add_action( 'admin_enqueue_scripts', 'neonmagic_admin_enqueue' );

// Save rating when comment is submitted
function neonmagic_save_comment_rating( $comment_id ) {
    if ( isset( $_POST['nm_rating'] ) ) {
        $rating = intval( $_POST['nm_rating'] );
        $rating = max( 1, min( 5, $rating ) );
        add_comment_meta( $comment_id, 'nm_rating', $rating, true );
    }
}
add_action( 'comment_post', 'neonmagic_save_comment_rating' );

// Add rating field to comment form
function neonmagic_comment_rating_field( $fields ) {
    $fields['nm_rating'] = '<div class="comment-form-nm-rating mb-4">
        <label class="font-mono font-bold text-sm uppercase mb-2 block">' . esc_html__( 'Your Rating', 'neonmagic' ) . '</label>
        <div class="nm-star-rating flex gap-1" data-field="nm_rating">
            ' . neonmagic_star_input_html() . '
        </div>
        <input type="hidden" name="nm_rating" id="nm_rating_value" value="5" />
    </div>';
    return $fields;
}
add_filter( 'comment_form_default_fields', 'neonmagic_comment_rating_field' );

function neonmagic_star_input_html() {
    $html = '';
    for ( $i = 1; $i <= 5; $i++ ) {
        $html .= '<button type="button" data-value="' . $i . '" class="nm-star-btn w-8 h-8 border-2 border-black flex items-center justify-center bg-pop-yellow text-black transition-all hover:scale-110" aria-label="' . sprintf( esc_attr__( '%d stars', 'neonmagic' ), $i ) . '">
            <i class="fa fa-star text-sm"></i>
        </button>';
    }
    return $html;
}

// Custom Search Form
function neonmagic_search_form( $form ) {
    $action  = esc_url( home_url( '/' ) );
    $value   = get_search_query();
    $label   = esc_attr__( 'Search downloads...', 'neonmagic' );
    $btn_lbl = esc_attr__( 'Search', 'neonmagic' );

    return '<form role="search" method="get" action="' . $action . '" class="flex w-full relative group" id="quicksearch">
        <div class="absolute inset-0 bg-pop-black translate-x-1 translate-y-1"></div>
        <input type="search"
               name="s"
               value="' . esc_attr( $value ) . '"
               placeholder="' . $label . '"
               class="relative w-full h-12 bg-white border-2 border-pop-black px-4 font-mono text-base placeholder:text-gray-500 focus:outline-none focus:bg-white transition-colors"
               autocomplete="off" />
        <button type="submit"
                class="relative w-16 h-12 bg-pop-black text-white border-2 border-pop-black hover:bg-pop-pink hover:text-black transition-colors flex items-center justify-center text-xl"
                aria-label="' . $btn_lbl . '">
            <i class="fa fa-search"></i>
        </button>
    </form>';
}
add_filter( 'get_search_form', 'neonmagic_search_form' );

// Excerpt Length
function neonmagic_excerpt_length( $length ) {
    return 20;
}
add_filter( 'excerpt_length', 'neonmagic_excerpt_length' );

function neonmagic_excerpt_more( $more ) {
    return '...';
}
add_filter( 'excerpt_more', 'neonmagic_excerpt_more' );

// Body Classes
function neonmagic_body_classes( $classes ) {
    if ( is_singular() ) {
        $classes[] = 'single-post-page';
    }
    if ( is_archive() || is_home() ) {
        $classes[] = 'archive-page';
    }
    return $classes;
}
add_filter( 'body_class', 'neonmagic_body_classes' );

remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
