<?php
/**
 * NeonMagic Helper Functions
 *
 * @package NeonMagic
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Returns true if the post/download is a paid item.
 */
function nm_is_paid( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    return (bool) get_post_meta( $post_id, '_nm_is_paid', true );
}

/**
 * Get the price string for a paid item.
 */
function nm_get_price( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    return sanitize_text_field( get_post_meta( $post_id, '_nm_price', true ) );
}

/**
 * Get the external buy URL for a paid item.
 */
function nm_get_buy_url( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    return esc_url( get_post_meta( $post_id, '_nm_buy_url', true ) );
}

/**
 * Get the download URL for a free item.
 */
function nm_get_download_url( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    return esc_url( get_post_meta( $post_id, '_nm_download_url', true ) );
}

/**
 * Get the file type / extension (e.g. "ZIP").
 */
function nm_get_file_type( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    return strtoupper( sanitize_text_field( get_post_meta( $post_id, '_nm_file_type', true ) ) );
}

/**
 * Get file size string (e.g. "48 MB").
 */
function nm_get_file_size( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    return sanitize_text_field( get_post_meta( $post_id, '_nm_file_size', true ) );
}

/**
 * Get compatibility string (e.g. "Figma").
 */
function nm_get_compatibility( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    return sanitize_text_field( get_post_meta( $post_id, '_nm_compatibility', true ) );
}

/**
 * Get version string (e.g. "v2.1.0").
 */
function nm_get_version( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    return sanitize_text_field( get_post_meta( $post_id, '_nm_version', true ) );
}

/**
 * Get download count.
 */
function nm_get_download_count( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    return intval( get_post_meta( $post_id, '_nm_download_count', true ) );
}

/**
 * Get license string.
 */
function nm_get_license( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    $license = get_post_meta( $post_id, '_nm_license', true );
    return $license ?: ( nm_is_paid( $post_id ) ? __( 'Commercial License', 'neonmagic' ) : __( 'Free — Personal & Commercial', 'neonmagic' ) );
}

/**
 * Get attribution string.
 */
function nm_get_attribution( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    $attr = get_post_meta( $post_id, '_nm_attribution', true );
    return $attr ?: __( 'Not Required', 'neonmagic' );
}

/**
 * Get "What's Included" array (one item per line in meta).
 */
function nm_get_whats_included( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    $raw = get_post_meta( $post_id, '_nm_whats_included', true );
    if ( ! $raw ) return [];
    return array_filter( array_map( 'trim', explode( "\n", $raw ) ) );
}

/**
 * Get requirements array.
 */
function nm_get_requirements( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    $raw = get_post_meta( $post_id, '_nm_requirements', true );
    if ( ! $raw ) return [];
    $items = [];
    foreach ( array_filter( array_map( 'trim', explode( "\n", $raw ) ) ) as $line ) {
        $parts = explode( '|', $line, 2 );
        $items[] = [
            'type' => isset( $parts[0] ) ? trim( $parts[0] ) : 'check',
            'text' => isset( $parts[1] ) ? trim( $parts[1] ) : trim( $line ),
        ];
    }
    return $items;
}

/**
 * Get changelog array.
 */
function nm_get_changelog( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    $raw = get_post_meta( $post_id, '_nm_changelog', true );
    if ( ! $raw ) return [];
    $data = json_decode( $raw, true );
    return is_array( $data ) ? $data : [];
}

/**
 * Get gallery image IDs as array.
 */
function nm_get_gallery_ids( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    $raw = get_post_meta( $post_id, '_nm_gallery', true );
    if ( ! $raw ) return [];
    return array_filter( array_map( 'intval', explode( ',', $raw ) ) );
}

/**
 * Get the average rating for a post based on approved comments.
 */
function nm_get_average_rating( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();

    $comments = get_comments( [
        'post_id' => $post_id,
        'status'  => 'approve',
        'type'    => 'comment',
    ] );

    if ( empty( $comments ) ) return 0;

    $total  = 0;
    $count  = 0;
    foreach ( $comments as $comment ) {
        $rating = intval( get_comment_meta( $comment->comment_ID, 'nm_rating', true ) );
        if ( $rating > 0 ) {
            $total += $rating;
            $count++;
        }
    }

    return $count > 0 ? round( $total / $count, 1 ) : 0;
}

/**
 * Get count of approved reviews (comments with a rating).
 */
function nm_get_review_count( $post_id = null ) {
    $post_id = $post_id ?: get_the_ID();
    $comments = get_comments( [
        'post_id' => $post_id,
        'status'  => 'approve',
        'type'    => 'comment',
    ] );
    $count = 0;
    foreach ( $comments as $comment ) {
        if ( get_comment_meta( $comment->comment_ID, 'nm_rating', true ) ) {
            $count++;
        }
    }
    return $count;
}

/**
 * Render star icons given a numeric rating (0–5).
 */
function nm_render_stars( $rating, $classes = 'text-pop-yellow text-xl', $stroke = true ) {
    $html = '';
    for ( $i = 1; $i <= 5; $i++ ) {
        $icon  = $i <= $rating ? 'fa-star' : 'fa-star';
        $alpha = $i <= $rating ? '1' : '0.3';
        $style = $stroke ? ' style="-webkit-text-stroke: 1.5px #000; opacity:' . $alpha . ';"' : ' style="opacity:' . $alpha . ';"';
        $html .= '<i class="fa ' . $icon . ' ' . $classes . '"' . $style . '></i>';
    }
    return $html;
}

/**
 * Custom pagination with a fixed spread of 8 page numbers.
 */
function nm_pagination( $total_pages = 0, $current = 0 ) {
    global $wp_query;

    if ( ! $total_pages ) {
        $total_pages = isset( $wp_query->max_num_pages ) ? (int) $wp_query->max_num_pages : 1;
    }
    if ( ! $current ) {
        $current = max( 1, get_query_var( 'paged' ) );
    }
    if ( $total_pages <= 1 ) {
        return '';
    }

    $spread = 8; // Always maintain this many numbered buttons

    if ( $total_pages <= $spread ) {
        $window_start = 1;
        $window_end   = $total_pages;
    } else {
        $window_start = $current - 3;
        $window_end   = $window_start + ( $spread - 1 );

        // Clamp to valid range, keeping the window size fixed
        if ( $window_start < 1 ) {
            $window_start = 1;
            $window_end   = $spread;
        }
        if ( $window_end > $total_pages ) {
            $window_end   = $total_pages;
            $window_start = max( 1, $window_end - ( $spread - 1 ) );
        }
    }

    $show_first          = $window_start > 1;
    $show_start_ellipsis = $window_start > 2;
    $show_last           = $window_end < $total_pages;
    $show_end_ellipsis   = $window_end < ( $total_pages - 1 );

    $base_cls    = 'w-12 h-12 flex items-center justify-center border-2 border-pop-black font-bold text-lg shadow-hard-sm';
    $active_cls  = $base_cls . ' bg-pop-black text-white cursor-default';
    $link_cls    = $base_cls . ' bg-white hover:bg-pop-yellow hover:-translate-y-1 transition-transform';
    $dots_cls    = 'w-12 h-12 flex items-center justify-center font-black tracking-widest';

    ob_start();
    ?>
    <div class="flex flex-wrap justify-center gap-3">

        <?php if ( $show_first ) : ?>
            <a href="<?php echo esc_url( get_pagenum_link( 1 ) ); ?>" class="<?php echo $link_cls; ?>">1</a>
        <?php endif; ?>

        <?php if ( $show_start_ellipsis ) : ?>
            <span class="<?php echo $dots_cls; ?>">&#x22EF;</span>
        <?php endif; ?>

        <?php for ( $p = $window_start; $p <= $window_end; $p++ ) : ?>
            <?php if ( $p === $current ) : ?>
                <span class="<?php echo $active_cls; ?>" aria-current="page"><?php echo $p; ?></span>
            <?php else : ?>
                <a href="<?php echo esc_url( get_pagenum_link( $p ) ); ?>" class="<?php echo $link_cls; ?>"><?php echo $p; ?></a>
            <?php endif; ?>
        <?php endfor; ?>

        <?php if ( $show_end_ellipsis ) : ?>
            <span class="<?php echo $dots_cls; ?>">&#x22EF;</span>
        <?php endif; ?>

        <?php if ( $show_last ) : ?>
            <a href="<?php echo esc_url( get_pagenum_link( $total_pages ) ); ?>" class="<?php echo $link_cls; ?>"><?php echo $total_pages; ?></a>
        <?php endif; ?>

    </div>
    <?php
    return ob_get_clean();
}

// Breadcrumbs
function nm_breadcrumb() {
    $home_url   = home_url( '/' );
    $home_label = __( 'Downloads', 'neonmagic' );

    echo '<nav class="mb-8 font-mono font-bold text-sm flex items-center gap-2 flex-wrap" aria-label="' . esc_attr__( 'Breadcrumb', 'neonmagic' ) . '">';
    echo '<a href="' . esc_url( $home_url ) . '" class="hover:underline decoration-4 decoration-pop-cyan">' . esc_html( $home_label ) . '</a>';

    if ( is_category() || is_single() ) {
        $categories = get_the_category();
        if ( $categories ) {
            $cat = $categories[0];
            echo '<span class="font-black">&#x203A;</span>';
            echo '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '" class="hover:underline decoration-4 decoration-pop-cyan">' . esc_html( $cat->name ) . '</a>';
        }
    }

    if ( is_single() ) {
        echo '<span class="font-black">&#x203A;</span>';
        echo '<span class="text-gray-500">' . esc_html( get_the_title() ) . '</span>';
    } elseif ( is_search() ) {
        echo '<span class="font-black">&#x203A;</span>';
        echo '<span class="text-gray-500">' . sprintf( esc_html__( 'Search: %s', 'neonmagic' ), get_search_query() ) . '</span>';
    } elseif ( is_category() ) {
        echo '<span class="font-black">&#x203A;</span>';
        echo '<span class="text-gray-500">' . single_cat_title( '', false ) . '</span>';
    } elseif ( is_tag() ) {
        echo '<span class="font-black">&#x203A;</span>';
        echo '<span class="text-gray-500">' . sprintf( esc_html__( 'Tag: %s', 'neonmagic' ), single_tag_title( '', false ) ) . '</span>';
    }

    echo '</nav>';
}

// Page Title Helper
function nm_archive_title() {
    if ( is_home() ) {
        return __( 'Digital Downloads', 'neonmagic' );
    } elseif ( is_search() ) {
        return sprintf( __( 'Search: %s', 'neonmagic' ), get_search_query() );
    } elseif ( is_category() ) {
        return single_cat_title( '', false );
    } elseif ( is_tag() ) {
        return sprintf( __( 'Tag: %s', 'neonmagic' ), single_tag_title( '', false ) );
    } elseif ( is_archive() ) {
        return get_the_archive_title();
    }
    return __( 'Digital Downloads', 'neonmagic' );
}

// Increment Download Count
function nm_increment_download_count( $post_id ) {
    $post_id = intval( $post_id );
    $count   = intval( get_post_meta( $post_id, '_nm_download_count', true ) );
    update_post_meta( $post_id, '_nm_download_count', $count + 1 );
    return $count + 1;
}

// Icon Type
function nm_req_icon( $type ) {
    switch ( $type ) {
        case 'info':  return [ 'fa-info-circle',  'text-blue-500'  ];
        case 'cross': return [ 'fa-times-circle', 'text-gray-400'  ];
        default:      return [ 'fa-check-circle', 'text-green-600' ];
    }
}

// Changelog Color Class
function nm_changelog_color_class( $color ) {
    $map = [
        'cyan'   => 'bg-pop-cyan',
        'yellow' => 'bg-pop-yellow',
        'pink'   => 'bg-pop-pink',
        'white'  => 'bg-white',
    ];
    return isset( $map[ $color ] ) ? $map[ $color ] : 'bg-pop-cyan';
}

// Related Posts
function nm_get_related_posts( $post_id = null, $count = 4 ) {
    $post_id    = $post_id ?: get_the_ID();
    $categories = get_the_category( $post_id );
    $cat_ids    = wp_list_pluck( $categories, 'term_id' );

    return new WP_Query( [
        'post_type'           => 'post',
        'posts_per_page'      => $count,
        'post__not_in'        => [ $post_id ],
        'category__in'        => $cat_ids,
        'orderby'             => 'rand',
        'no_found_rows'       => true,
        'ignore_sticky_posts' => true,
    ] );
}

// Format Download Count
function nm_format_count( $n ) {
    return number_format( (int) $n );
}
