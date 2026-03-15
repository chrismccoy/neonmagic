<?php
/**
 * NeonMagic — Single Post Template
 *
 * @package NeonMagic
 */

get_header();

while ( have_posts() ) :
    the_post();

    $post_id        = get_the_ID();
    $is_paid        = nm_is_paid( $post_id );
    $price          = nm_get_price( $post_id );
    $buy_url        = nm_get_buy_url( $post_id );
    $download_url   = nm_get_download_url( $post_id );
    $file_type      = nm_get_file_type( $post_id );
    $file_size      = nm_get_file_size( $post_id );
    $compat         = nm_get_compatibility( $post_id );
    $version        = nm_get_version( $post_id );
    $dl_count       = nm_get_download_count( $post_id );
    $license        = nm_get_license( $post_id );
    $attribution    = nm_get_attribution( $post_id );
    $whats_included = nm_get_whats_included( $post_id );
    $requirements   = nm_get_requirements( $post_id );
    $changelog      = nm_get_changelog( $post_id );
    $gallery_ids    = nm_get_gallery_ids( $post_id );
    $avg_rating     = nm_get_average_rating( $post_id );
    $review_count   = nm_get_review_count( $post_id );

    // Main preview image
    $main_img = has_post_thumbnail()
        ? get_the_post_thumbnail_url( $post_id, 'neonmagic-square' )
        : 'https://placehold.co/600x600/FFDE59/000000?text=FILE_PREVIEW&font=roboto';

    // Build gallery: featured image first, then extra gallery images
    $gallery_thumbs   = [];
    $gallery_thumbs[] = [
        'full'  => $main_img,
        'thumb' => has_post_thumbnail() ? get_the_post_thumbnail_url( $post_id, 'neonmagic-thumb' ) : $main_img,
        'alt'   => get_the_title(),
    ];
    foreach ( $gallery_ids as $gid ) {
        $full  = wp_get_attachment_image_src( $gid, 'neonmagic-square' );
        $thumb = wp_get_attachment_image_src( $gid, 'neonmagic-thumb' );
        if ( $full ) {
            $gallery_thumbs[] = [
                'full'  => $full[0],
                'thumb' => $thumb ? $thumb[0] : $full[0],
                'alt'   => get_post_meta( $gid, '_wp_attachment_image_alt', true ) ?: get_the_title(),
            ];
        }
    }

    $categories    = get_the_category( $post_id );
    $primary_cat   = $categories ? $categories[0] : null;

    $post_date = get_the_date( 'M Y' );

    $total_comments = get_comments_number( $post_id );
?>

<div class="p-6 md:p-8 bg-gray-50 flex-grow">

    <?php nm_breadcrumb(); ?>

    <div class="flex flex-col lg:flex-row gap-10 mb-16">

        <div class="w-full lg:w-1/2 flex-shrink-0">

            <div class="relative border-4 border-pop-black shadow-hard-xl mb-8 overflow-hidden aspect-square bg-pop-yellow group">
                <img id="main-preview-img"
                     src="<?php echo esc_url( $main_img ); ?>"
                     alt="<?php the_title_attribute(); ?>"
                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105" />

                <div class="absolute top-4 left-4">
                    <div class="relative inline-block">
                        <div class="absolute inset-0 bg-pop-black translate-x-1 translate-y-1"></div>
                        <?php if ( $is_paid ) : ?>
                            <span class="relative bg-pop-yellow border-2 border-pop-black px-4 py-1 font-sans font-black text-xl uppercase">
                                <?php echo $price ? '$' . esc_html( $price ) : esc_html__( 'PAID', 'neonmagic' ); ?>
                            </span>
                        <?php else : ?>
                            <span class="relative bg-pop-cyan border-2 border-pop-black px-4 py-1 font-sans font-black text-xl uppercase">
                                <?php esc_html_e( 'FREE', 'neonmagic' ); ?>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ( $file_type ) : ?>
                    <div class="absolute bottom-4 right-4 bg-pop-black text-white font-mono font-bold text-sm px-3 py-1 border-2 border-white">
                        .<?php echo esc_html( $file_type ); ?>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ( count( $gallery_thumbs ) > 1 ) : ?>
            <div class="grid grid-cols-4 gap-3" id="thumb-strip" role="list" aria-label="<?php esc_attr_e( 'Preview thumbnails', 'neonmagic' ); ?>">
                <?php foreach ( $gallery_thumbs as $idx => $thumb ) : ?>
                    <button type="button"
                            data-thumb="<?php echo esc_attr( $idx ); ?>"
                            data-src="<?php echo esc_url( $thumb['full'] ); ?>"
                            class="nm-thumb-btn aspect-square overflow-hidden transition-all <?php echo $idx === 0 ? 'border-4 border-pop-black shadow-hard-sm' : 'border-2 border-gray-300'; ?>"
                            aria-label="<?php echo esc_attr( sprintf( __( 'Preview image %d', 'neonmagic' ), $idx + 1 ) ); ?>"
                            role="listitem">
                        <img src="<?php echo esc_url( $thumb['thumb'] ); ?>"
                             alt="<?php echo esc_attr( $thumb['alt'] ); ?>"
                             loading="lazy"
                             class="w-full h-full object-cover" />
                    </button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <div class="w-full lg:w-1/2 flex flex-col">

            <div class="flex items-center gap-2 mb-4 flex-wrap">
                <?php if ( $primary_cat ) : ?>
                    <a href="<?php echo esc_url( get_category_link( $primary_cat->term_id ) ); ?>"
                       class="inline-block bg-pop-cyan border-2 border-pop-black px-3 py-1 font-mono font-bold text-sm uppercase hover:bg-pop-yellow transition-colors shadow-[2px_2px_0_0_#000]">
                        <?php echo esc_html( $primary_cat->name ); ?>
                    </a>
                <?php endif; ?>
                <?php if ( $file_type ) : ?>
                    <span class="bg-pop-black text-white font-mono font-bold text-xs px-3 py-1 border-2 border-pop-black">
                        .<?php echo esc_html( $file_type ); ?>
                    </span>
                <?php endif; ?>
                <?php if ( $compat ) : ?>
                    <span class="bg-white border-2 border-pop-black font-mono font-bold text-xs px-3 py-1 flex items-center gap-1">
                        <i class="fa fa-puzzle-piece" aria-hidden="true"></i>
                        <?php echo esc_html( $compat ); ?>
                    </span>
                <?php endif; ?>
            </div>

            <h1 class="font-sans font-black text-5xl md:text-6xl uppercase tracking-tighter leading-none mb-6">
                <?php the_title(); ?>
            </h1>

            <div class="flex items-center gap-3 mb-6 border-b-2 border-dashed border-gray-300 pb-6 flex-wrap">
                <?php if ( $avg_rating > 0 ) : ?>
                    <div class="flex gap-1" aria-label="<?php echo esc_attr( sprintf( __( 'Rating: %s out of 5', 'neonmagic' ), $avg_rating ) ); ?>">
                        <?php echo nm_render_stars( round( $avg_rating ) ); ?>
                    </div>
                    <span class="font-mono font-bold text-sm">
                        (<?php echo esc_html( nm_format_count( $review_count ) ); ?> <?php echo _n( 'review', 'reviews', $review_count, 'neonmagic' ); ?>)
                    </span>
                <?php elseif ( comments_open() ) : ?>
                    <span class="font-mono text-sm text-gray-400">
                        <?php esc_html_e( 'No reviews yet', 'neonmagic' ); ?>
                    </span>
                <?php endif; ?>

                <?php if ( $dl_count > 0 ) : ?>
                    <span class="font-mono text-xs text-gray-400 flex items-center gap-1 ml-2">
                        <i class="fa fa-download" aria-hidden="true"></i>
                        <?php echo esc_html( nm_format_count( $dl_count ) ); ?> <?php esc_html_e( 'downloads', 'neonmagic' ); ?>
                    </span>
                <?php endif; ?>
            </div>

            <div class="relative inline-block mb-8 self-start">
                <div class="absolute inset-0 bg-pop-black translate-x-2 translate-y-2"></div>
                <?php if ( $is_paid && $price ) : ?>
                    <div class="relative bg-pop-yellow border-4 border-pop-black px-8 py-4 flex items-center gap-6">
                        <span class="font-sans font-black text-7xl leading-none" aria-label="<?php echo esc_attr( '$' . $price ); ?>">
                            <span class="text-3xl align-super" aria-hidden="true">$</span><?php echo esc_html( $price ); ?>
                        </span>
                        <div class="flex flex-col gap-1">
                            <?php if ( $file_size ) : ?>
                                <span class="font-mono font-bold text-xs uppercase text-gray-600 flex items-center gap-1">
                                    <i class="fa fa-hdd" aria-hidden="true"></i>
                                    <?php echo esc_html( $file_size ); ?>
                                </span>
                            <?php endif; ?>
                            <?php if ( $version ) : ?>
                                <span class="font-mono font-bold text-xs uppercase text-gray-600 flex items-center gap-1">
                                    <i class="fa fa-tag" aria-hidden="true"></i>
                                    <?php echo esc_html( $version ); ?>
                                </span>
                            <?php endif; ?>
                            <span class="font-mono font-bold text-xs uppercase text-gray-600 flex items-center gap-1">
                                <i class="fa fa-calendar-alt" aria-hidden="true"></i>
                                <?php echo esc_html( $post_date ); ?>
                            </span>
                        </div>
                    </div>
                <?php else : ?>
                    <div class="relative bg-pop-cyan border-4 border-pop-black px-8 py-4 flex items-center gap-6">
                        <span class="font-sans font-black text-7xl leading-none uppercase"><?php esc_html_e( 'Free', 'neonmagic' ); ?></span>
                        <div class="flex flex-col gap-1">
                            <?php if ( $file_size ) : ?>
                                <span class="font-mono font-bold text-xs uppercase text-gray-600 flex items-center gap-1">
                                    <i class="fa fa-hdd" aria-hidden="true"></i>
                                    <?php echo esc_html( $file_size ); ?>
                                </span>
                            <?php endif; ?>
                            <?php if ( $version ) : ?>
                                <span class="font-mono font-bold text-xs uppercase text-gray-600 flex items-center gap-1">
                                    <i class="fa fa-tag" aria-hidden="true"></i>
                                    <?php echo esc_html( $version ); ?>
                                </span>
                            <?php endif; ?>
                            <span class="font-mono font-bold text-xs uppercase text-gray-600 flex items-center gap-1">
                                <i class="fa fa-calendar-alt" aria-hidden="true"></i>
                                <?php echo esc_html( $post_date ); ?>
                            </span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <?php if ( has_excerpt() ) : ?>
                <p class="font-mono font-medium text-base leading-relaxed mb-8 border-l-4 border-pop-cyan pl-4">
                    <?php echo esc_html( get_the_excerpt() ); ?>
                </p>
            <?php endif; ?>

            <?php if ( $whats_included ) : ?>
            <div class="mb-8">
                <p class="font-mono font-bold text-sm uppercase mb-3"><?php esc_html_e( "What's Inside:", 'neonmagic' ); ?></p>
                <div class="grid grid-cols-2 gap-2">
                    <?php foreach ( $whats_included as $item ) : ?>
                        <div class="flex items-center gap-2 font-mono font-bold text-sm">
                            <span class="w-5 h-5 bg-pop-cyan border-2 border-pop-black flex-shrink-0 flex items-center justify-center font-black text-xs" aria-hidden="true">✓</span>
                            <?php echo esc_html( $item ); ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <div class="relative mb-4">
                <div class="absolute inset-0 bg-pop-black translate-x-3 translate-y-3"></div>

                <?php if ( $is_paid && $buy_url ) : ?>
                    <a href="<?php echo esc_url( $buy_url ); ?>"
                       target="_blank"
                       rel="noopener noreferrer"
                       id="buy-btn"
                       class="relative w-full h-20 bg-pop-yellow border-4 border-pop-black font-sans font-black text-3xl uppercase tracking-tight hover:bg-pop-pink active:translate-x-1 active:translate-y-1 active:shadow-none transition-all flex items-center justify-center gap-4 shadow-[6px_6px_0_0_#000] hover:shadow-[8px_8px_0_0_#000]">
                        <i class="fa fa-shopping-cart text-4xl" aria-hidden="true"></i>
                        <?php esc_html_e( 'Buy Now', 'neonmagic' ); ?>
                        <?php if ( $price ) : ?>
                            <span class="text-2xl ml-2">$<?php echo esc_html( $price ); ?></span>
                        <?php endif; ?>
                    </a>
                <?php else : ?>
                    <button type="button"
                            id="download-btn"
                            class="relative w-full h-20 bg-pop-cyan border-4 border-pop-black font-sans font-black text-3xl uppercase tracking-tight hover:bg-pop-yellow active:translate-x-1 active:translate-y-1 active:shadow-none transition-all flex items-center justify-center gap-4 shadow-[6px_6px_0_0_#000] hover:shadow-[8px_8px_0_0_#000]"
                            data-post-id="<?php echo esc_attr( $post_id ); ?>"
                            data-download-url="<?php echo esc_attr( $download_url ); ?>">
                        <i class="fa fa-download text-4xl" aria-hidden="true"></i>
                        <?php esc_html_e( 'Free Download', 'neonmagic' ); ?>
                    </button>
                <?php endif; ?>
            </div>

            <?php if ( ! $is_paid ) : ?>
            <p class="font-mono text-xs text-gray-500 text-center mb-8 flex items-center justify-center gap-2 flex-wrap">
                <i class="fa fa-lock-open" aria-hidden="true"></i>
                <?php esc_html_e( 'No account required', 'neonmagic' ); ?>
                &nbsp;&middot;&nbsp;
                <i class="fa fa-bolt" aria-hidden="true"></i>
                <?php esc_html_e( 'Instant access', 'neonmagic' ); ?>
                &nbsp;&middot;&nbsp;
                <i class="fa fa-infinity" aria-hidden="true"></i>
                <?php esc_html_e( 'Free forever', 'neonmagic' ); ?>
            </p>
            <?php else : ?>
            <p class="font-mono text-xs text-gray-500 text-center mb-8 flex items-center justify-center gap-2 flex-wrap">
                <i class="fa fa-shield-alt" aria-hidden="true"></i>
                <?php esc_html_e( 'Secure checkout', 'neonmagic' ); ?>
                &nbsp;&middot;&nbsp;
                <i class="fa fa-bolt" aria-hidden="true"></i>
                <?php esc_html_e( 'Instant download after purchase', 'neonmagic' ); ?>
            </p>
            <?php endif; ?>

            <div class="flex flex-wrap gap-3 mb-8">
                <button type="button"
                        id="share-btn"
                        data-url="<?php echo esc_attr( get_the_permalink() ); ?>"
                        data-title="<?php the_title_attribute(); ?>"
                        class="btn-physics flex items-center gap-2 px-4 py-2 bg-white border-2 border-pop-black font-mono font-bold text-sm uppercase shadow-hard-sm hover:bg-pop-yellow">
                    <i class="fa fa-share-alt" aria-hidden="true"></i>
                    <?php esc_html_e( 'Share', 'neonmagic' ); ?>
                </button>

                <button type="button"
                        id="save-btn"
                        data-post-id="<?php echo esc_attr( $post_id ); ?>"
                        class="btn-physics flex items-center gap-2 px-4 py-2 bg-white border-2 border-pop-black font-mono font-bold text-sm uppercase shadow-hard-sm hover:bg-pop-pink">
                    <i class="fa fa-heart" aria-hidden="true"></i>
                    <span id="save-label"><?php esc_html_e( 'Save', 'neonmagic' ); ?></span>
                </button>

                <a href="<?php echo esc_url( add_query_arg( [ 'report' => $post_id ], home_url( '/contact/' ) ) ); ?>"
                   class="btn-physics flex items-center gap-2 px-4 py-2 bg-white border-2 border-pop-black font-mono font-bold text-sm uppercase shadow-hard-sm hover:bg-pop-cyan">
                    <i class="fa fa-flag" aria-hidden="true"></i>
                    <?php esc_html_e( 'Report', 'neonmagic' ); ?>
                </a>
            </div>

            <div class="grid grid-cols-4 gap-3 border-t-4 border-pop-black pt-6">
                <div class="flex flex-col items-center gap-2 text-center">
                    <div class="w-10 h-10 bg-pop-cyan border-2 border-black flex items-center justify-center rounded-full">
                        <i class="fa fa-bolt text-lg" aria-hidden="true"></i>
                    </div>
                    <span class="font-mono font-bold text-xs uppercase leading-tight"><?php esc_html_e( 'Instant DL', 'neonmagic' ); ?></span>
                </div>
                <div class="flex flex-col items-center gap-2 text-center">
                    <div class="w-10 h-10 bg-pop-yellow border-2 border-black flex items-center justify-center rounded-full">
                        <i class="fa fa-shield-virus text-lg" aria-hidden="true"></i>
                    </div>
                    <span class="font-mono font-bold text-xs uppercase leading-tight"><?php esc_html_e( 'Clean File', 'neonmagic' ); ?></span>
                </div>
                <div class="flex flex-col items-center gap-2 text-center">
                    <div class="w-10 h-10 bg-pop-pink border-2 border-black flex items-center justify-center rounded-full">
                        <i class="fa fa-file-contract text-lg" aria-hidden="true"></i>
                    </div>
                    <span class="font-mono font-bold text-xs uppercase leading-tight">
                        <?php echo $is_paid ? esc_html__( 'Commercial', 'neonmagic' ) : esc_html__( 'Free License', 'neonmagic' ); ?>
                    </span>
                </div>
                <div class="flex flex-col items-center gap-2 text-center">
                    <div class="w-10 h-10 bg-pop-cyan border-2 border-black flex items-center justify-center rounded-full">
                        <i class="fa fa-sync-alt text-lg" aria-hidden="true"></i>
                    </div>
                    <span class="font-mono font-bold text-xs uppercase leading-tight"><?php esc_html_e( 'Updated', 'neonmagic' ); ?></span>
                </div>
            </div>

        </div>
    </div>

    <div class="mb-16">

        <div class="flex flex-wrap border-b-4 border-pop-black gap-0" role="tablist" aria-label="<?php esc_attr_e( 'File details tabs', 'neonmagic' ); ?>">
            <?php
            $tabs = [
                'about'        => __( 'About',        'neonmagic' ),
                'files'        => __( 'File Details', 'neonmagic' ),
                'requirements' => __( 'Requirements', 'neonmagic' ),
                'changelog'    => __( 'Changelog',    'neonmagic' ),
                'reviews'      => sprintf( __( 'Reviews (%s)', 'neonmagic' ), $review_count ),
            ];
            $first = true;
            foreach ( $tabs as $key => $label ) :
                $active_cls  = $first ? 'bg-pop-black text-white' : 'bg-white hover:bg-pop-yellow transition-colors';
                $border_left = $first ? 'border-l-4 ' : '';
                $first       = false;
            ?>
            <button type="button"
                    onclick="nmSwitchTab('<?php echo esc_attr( $key ); ?>')"
                    id="tab-<?php echo esc_attr( $key ); ?>"
                    class="nm-tab-btn <?php echo $active_cls; ?> px-6 py-3 font-sans font-black uppercase text-sm <?php echo $border_left; ?>border-r-4 border-t-4 border-pop-black -mb-1"
                    role="tab"
                    aria-selected="<?php echo $first ? 'false' : ( $key === 'about' ? 'true' : 'false' ); ?>"
                    aria-controls="panel-<?php echo esc_attr( $key ); ?>">
                <?php echo esc_html( $label ); ?>
            </button>
            <?php endforeach; ?>
        </div>

        <div class="border-4 border-t-0 border-pop-black bg-white p-8 shadow-hard-lg">

            <div id="panel-about" class="nm-tab-panel" role="tabpanel" aria-labelledby="tab-about">
                <h3 class="font-sans font-black text-2xl uppercase mb-4"><?php esc_html_e( 'About This File', 'neonmagic' ); ?></h3>
                <div class="space-y-4 font-mono font-medium leading-relaxed max-w-3xl prose-custom">
                    <?php the_content(); ?>
                </div>
            </div>

            <div id="panel-files" class="nm-tab-panel hidden" role="tabpanel" aria-labelledby="tab-files">
                <h3 class="font-sans font-black text-2xl uppercase mb-4"><?php esc_html_e( 'File Details', 'neonmagic' ); ?></h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-0 max-w-2xl border-2 border-pop-black">
                    <?php
                    $details = array_filter( [
                        __( 'File Format',     'neonmagic' ) => $file_type ? '.' . $file_type . ' ' . __( 'Archive', 'neonmagic' ) : '',
                        __( 'File Size',       'neonmagic' ) => $file_size,
                        __( 'Version',         'neonmagic' ) => $version,
                        __( 'Last Updated',    'neonmagic' ) => get_the_modified_date( 'F d, Y' ),
                        __( 'License',         'neonmagic' ) => $license,
                        __( 'Attribution',     'neonmagic' ) => $attribution,
                        __( 'Total Downloads', 'neonmagic' ) => $dl_count > 0 ? nm_format_count( $dl_count ) : '',
                        __( 'Compatibility',   'neonmagic' ) => $compat,
                    ] );

                    $row_idx = 0;
                    foreach ( $details as $label => $value ) :
                        $bg = $row_idx % 2 === 0 ? 'bg-white' : 'bg-gray-50';
                        $row_idx++;
                    ?>
                        <div class="<?php echo $bg; ?> border-b-2 border-r-2 border-pop-black p-3">
                            <span class="font-mono font-bold text-xs uppercase text-gray-500">
                                <?php echo esc_html( $label ); ?>
                            </span>
                        </div>
                        <div class="<?php echo $bg; ?> border-b-2 border-pop-black p-3">
                            <span class="font-mono font-bold text-sm">
                                <?php echo esc_html( $value ); ?>
                            </span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div id="panel-requirements" class="nm-tab-panel hidden" role="tabpanel" aria-labelledby="tab-requirements">
                <h3 class="font-sans font-black text-2xl uppercase mb-4"><?php esc_html_e( 'Requirements', 'neonmagic' ); ?></h3>
                <?php if ( $requirements ) : ?>
                    <div class="space-y-3 max-w-xl">
                        <?php foreach ( $requirements as $req ) :
                            [$icon, $color] = nm_req_icon( $req['type'] );
                        ?>
                            <div class="flex items-start gap-3 font-mono font-medium text-sm">
                                <i class="fa <?php echo esc_attr( $icon ); ?> <?php echo esc_attr( $color ); ?> mt-0.5 flex-shrink-0" aria-hidden="true"></i>
                                <span><?php echo esc_html( $req['text'] ); ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p class="font-mono text-gray-500 text-sm"><?php esc_html_e( 'No specific requirements listed.', 'neonmagic' ); ?></p>
                <?php endif; ?>
            </div>

            <div id="panel-changelog" class="nm-tab-panel hidden" role="tabpanel" aria-labelledby="tab-changelog">
                <h3 class="font-sans font-black text-2xl uppercase mb-6"><?php esc_html_e( 'Changelog', 'neonmagic' ); ?></h3>
                <?php if ( $changelog ) : ?>
                    <div class="space-y-6 max-w-2xl">
                        <?php foreach ( $changelog as $entry ) :
                            $color_cls = nm_changelog_color_class( $entry['color'] ?? 'cyan' );
                        ?>
                            <div class="border-4 border-pop-black shadow-hard-sm">
                                <div class="<?php echo esc_attr( $color_cls ); ?> border-b-4 border-pop-black px-4 py-2 flex items-center justify-between flex-wrap gap-2">
                                    <span class="font-sans font-black text-lg uppercase">
                                        <?php echo esc_html( $entry['version'] ?? '' ); ?>
                                    </span>
                                    <?php if ( ! empty( $entry['date'] ) ) : ?>
                                        <span class="font-mono font-bold text-sm flex items-center gap-1">
                                            <i class="fa fa-calendar-alt" aria-hidden="true"></i>
                                            <?php echo esc_html( $entry['date'] ); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                                <?php if ( ! empty( $entry['changes'] ) ) : ?>
                                    <ul class="p-4 space-y-2">
                                        <?php foreach ( (array) $entry['changes'] as $change ) : ?>
                                            <li class="flex items-start gap-2 font-mono font-medium text-sm">
                                                <span class="w-4 h-4 bg-pop-black flex-shrink-0 mt-0.5 flex items-center justify-center text-white" style="font-size:8px;" aria-hidden="true">›</span>
                                                <?php echo esc_html( $change ); ?>
                                            </li>
                                        <?php endforeach; ?>
                                    </ul>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p class="font-mono text-gray-500 text-sm"><?php esc_html_e( 'No changelog entries yet.', 'neonmagic' ); ?></p>
                <?php endif; ?>
            </div>

            <div id="panel-reviews" class="nm-tab-panel hidden" role="tabpanel" aria-labelledby="tab-reviews">
                <div class="flex flex-col md:flex-row gap-10">

                    <div class="flex-shrink-0">
                        <div class="relative inline-block">
                            <div class="absolute inset-0 bg-pop-black translate-x-2 translate-y-2"></div>
                            <div class="relative bg-pop-yellow border-4 border-pop-black p-6 text-center">
                                <div class="font-sans font-black text-7xl leading-none">
                                    <?php echo $avg_rating > 0 ? esc_html( number_format( $avg_rating, 1 ) ) : '—'; ?>
                                </div>
                                <div class="flex justify-center gap-1 my-2">
                                    <?php echo nm_render_stars( round( $avg_rating ), 'text-pop-black text-lg', false ); ?>
                                </div>
                                <div class="font-mono font-bold text-sm">
                                    <?php echo esc_html( nm_format_count( $review_count ) ); ?>
                                    <?php echo _n( 'review', 'reviews', $review_count, 'neonmagic' ); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex-grow space-y-6">
                        <?php
                        $comments = get_comments( [
                            'post_id' => $post_id,
                            'status'  => 'approve',
                            'type'    => 'comment',
                            'number'  => 10,
                        ] );

                        if ( $comments ) :
                            foreach ( $comments as $comment ) :
                                $c_rating = intval( get_comment_meta( $comment->comment_ID, 'nm_rating', true ) );
                                $c_name   = get_comment_author( $comment );
                                $c_date   = get_comment_date( 'M Y', $comment );
                                $c_text   = get_comment_text( $comment );
                            ?>
                            <div class="border-2 border-pop-black p-4 bg-white shadow-[4px_4px_0_0_#000]">
                                <div class="flex items-center justify-between mb-2 flex-wrap gap-2">
                                    <span class="font-sans font-black text-lg uppercase">
                                        <?php echo esc_html( strtoupper( $c_name ) ); ?>
                                    </span>
                                    <div class="flex items-center gap-3">
                                        <?php if ( $c_rating > 0 ) : ?>
                                            <div class="flex gap-0.5 text-pop-black">
                                                <?php echo nm_render_stars( $c_rating, 'text-pop-black text-sm', false ); ?>
                                            </div>
                                        <?php endif; ?>
                                        <span class="font-mono text-xs text-gray-400">
                                            <?php echo esc_html( $c_date ); ?>
                                        </span>
                                    </div>
                                </div>
                                <p class="font-mono font-medium text-sm leading-relaxed">
                                    <?php echo esc_html( $c_text ); ?>
                                </p>
                            </div>
                            <?php endforeach;
                        else : ?>
                            <p class="font-mono text-gray-500 text-sm"><?php esc_html_e( 'No reviews yet. Be the first!', 'neonmagic' ); ?></p>
                        <?php endif; ?>

                        <?php if ( comments_open( $post_id ) ) :
                            comment_form( [
                                'title_reply'          => __( 'Leave a Review', 'neonmagic' ),
                                'title_reply_before'   => '<h4 class="font-sans font-black text-xl uppercase mt-6 mb-4">',
                                'title_reply_after'    => '</h4>',
                                'label_submit'         => __( 'Post Review', 'neonmagic' ),
                                'submit_button'        => '<button type="submit" class="btn-physics bg-pop-black text-white px-6 py-3 font-sans font-black uppercase border-2 border-pop-black shadow-hard-sm hover:bg-pop-cyan hover:text-black">' . esc_html__( 'Post Review', 'neonmagic' ) . '</button>',
                                'submit_field'         => '<div class="form-submit mt-4">%1$s %2$s</div>',
                                'comment_field'        => '<div class="mb-4"><label for="comment" class="font-mono font-bold text-sm uppercase mb-2 block">' . esc_html__( 'Your Review', 'neonmagic' ) . '</label><textarea id="comment" name="comment" rows="5" required class="w-full border-2 border-pop-black p-3 font-mono text-sm focus:outline-none focus:border-pop-cyan" placeholder="' . esc_attr__( 'Share your experience with this file...', 'neonmagic' ) . '"></textarea></div>',
                                'fields'               => [
                                    'author' => '<div class="mb-4"><label for="author" class="font-mono font-bold text-sm uppercase mb-2 block">' . esc_html__( 'Name', 'neonmagic' ) . ' <span aria-hidden="true">*</span></label><input id="author" name="author" type="text" required class="w-full border-2 border-pop-black px-3 py-2 font-mono focus:outline-none focus:border-pop-cyan" /></div>',
                                    'email'  => '<div class="mb-4"><label for="email" class="font-mono font-bold text-sm uppercase mb-2 block">' . esc_html__( 'Email', 'neonmagic' ) . ' <span aria-hidden="true">*</span></label><input id="email" name="email" type="email" required class="w-full border-2 border-pop-black px-3 py-2 font-mono focus:outline-none focus:border-pop-cyan" /></div>',
                                    'nm_rating' => '<div class="mb-4"><label class="font-mono font-bold text-sm uppercase mb-2 block">' . esc_html__( 'Rating', 'neonmagic' ) . '</label><div class="nm-star-rating flex gap-1" id="nm-review-stars">' . neonmagic_star_input_html() . '</div><input type="hidden" name="nm_rating" id="nm_rating_value" value="5" /></div>',
                                ],
                            ] );
                        endif; ?>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <?php
    $related = nm_get_related_posts( $post_id, 4 );
    if ( $related->have_posts() ) :
        $section_title = $is_paid
            ? __( 'More Like This', 'neonmagic' )
            : __( 'More Free Files', 'neonmagic' );
    ?>
    <div class="mb-8">
        <div class="flex items-end justify-between mb-8 border-b-4 border-pop-black pb-2">
            <h2 class="font-sans font-black text-4xl uppercase tracking-tighter leading-none">
                <?php echo esc_html( $section_title ); ?>
            </h2>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
               class="font-mono font-bold text-sm hover:underline decoration-4 decoration-pop-cyan flex items-center gap-1">
                <?php esc_html_e( 'See All', 'neonmagic' ); ?>
                <i class="fa fa-arrow-right" aria-hidden="true"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
            while ( $related->have_posts() ) {
                $related->the_post();
                get_template_part( 'template-parts/content', 'related' );
            }
            wp_reset_postdata();
            ?>
        </div>
    </div>
    <?php endif; ?>

</div>

<?php endwhile; ?>

<?php get_footer(); ?>
