<?php
/**
 * NeonMagic — Main Index / Blog Template
 *
 * @package NeonMagic
 */

get_header();
?>

<div class="p-6 md:p-8 bg-gray-50 flex-grow">

    <?php if ( is_home() && ! is_paged() ) : ?>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
        <div class="bg-pop-yellow border-4 border-pop-black p-5 shadow-hard-md hover:shadow-hard-lg transition-all transform hover:-rotate-1">
            <div class="flex items-center gap-4 mb-3">
                <div class="w-12 h-12 bg-white border-2 border-black flex items-center justify-center rounded-full">
                    <i class="fa fa-bolt text-2xl" aria-hidden="true"></i>
                </div>
                <h4 class="font-sans font-black text-xl uppercase"><?php esc_html_e( 'Instant Access', 'neonmagic' ); ?></h4>
            </div>
            <p class="text-sm font-medium leading-tight">
                <?php esc_html_e( 'Download starts immediately. No waiting, no shipping, no box to lose.', 'neonmagic' ); ?>
            </p>
        </div>

        <div class="bg-pop-pink border-4 border-pop-black p-5 shadow-hard-md hover:shadow-hard-lg transition-all transform hover:rotate-1">
            <div class="flex items-center gap-4 mb-3">
                <div class="w-12 h-12 bg-white border-2 border-black flex items-center justify-center rounded-full">
                    <i class="fa fa-file-contract text-2xl" aria-hidden="true"></i>
                </div>
                <h4 class="font-sans font-black text-xl uppercase"><?php esc_html_e( 'Commercial License', 'neonmagic' ); ?></h4>
            </div>
            <p class="text-sm font-medium leading-tight">
                <?php esc_html_e( 'Use in client projects, personal work, or anything you can legally imagine doing.', 'neonmagic' ); ?>
            </p>
        </div>

        <div class="bg-pop-cyan border-4 border-pop-black p-5 shadow-hard-md hover:shadow-hard-lg transition-all transform hover:-rotate-1">
            <div class="flex items-center gap-4 mb-3">
                <div class="w-12 h-12 bg-white border-2 border-black flex items-center justify-center rounded-full">
                    <i class="fa fa-sync-alt text-2xl" aria-hidden="true"></i>
                </div>
                <h4 class="font-sans font-black text-xl uppercase"><?php esc_html_e( 'Free Updates', 'neonmagic' ); ?></h4>
            </div>
            <p class="text-sm font-medium leading-tight">
                <?php esc_html_e( 'Buy once, get all future updates at no extra charge. Forever. Probably.', 'neonmagic' ); ?>
            </p>
        </div>
    </div>
    <?php endif; ?>

    <div class="flex flex-col md:flex-row gap-8">

        <?php get_sidebar(); ?>

        <main class="flex-grow min-w-0" id="main-content" role="main">

            <div class="flex items-end justify-between mb-8 border-b-4 border-pop-black pb-2">
                <h1 class="font-sans font-black text-4xl md:text-5xl uppercase tracking-tighter leading-none text-pop-black">
                    <?php echo esc_html( nm_archive_title() ); ?>
                </h1>
                <?php if ( is_category() || is_tag() ) : ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
                       class="font-mono font-bold text-sm hover:underline decoration-4 decoration-pop-cyan flex items-center gap-1">
                        <i class="fa fa-arrow-left" aria-hidden="true"></i>
                        <?php esc_html_e( 'All Files', 'neonmagic' ); ?>
                    </a>
                <?php endif; ?>
            </div>

            <?php if ( have_posts() ) : ?>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6" id="file-grid"
                     data-cat-id="<?php echo esc_attr( is_category() ? get_queried_object_id() : 0 ); ?>"
                     data-tag-slug="<?php echo esc_attr( is_tag() ? get_queried_object()->slug : '' ); ?>"
                     data-search="<?php echo esc_attr( is_search() ? get_search_query() : '' ); ?>">
                    <?php
                    while ( have_posts() ) {
                        the_post();
                        get_template_part( 'template-parts/content', 'card' );
                    }
                    ?>
                </div>

                <?php
                global $wp_query;
                $total_pages = (int) $wp_query->max_num_pages;
                $current     = max( 1, get_query_var( 'paged' ) );
                ?>

                <div class="mt-16 flex flex-col items-center gap-6">

                    <?php if ( $total_pages > 1 && $current < $total_pages ) : ?>
                    <div class="w-full max-w-sm" id="load-more-wrap">
                        <button id="load-more-btn"
                                class="btn-physics block w-full bg-pop-cyan border-2 border-pop-black shadow-hard-sm py-4 text-center font-sans font-black uppercase text-xl"
                                data-page="<?php echo esc_attr( $current + 1 ); ?>"
                                data-max="<?php echo esc_attr( $total_pages ); ?>">
                            <?php esc_html_e( 'Load More Files', 'neonmagic' ); ?>
                        </button>
                    </div>
                    <?php endif; ?>

                    <?php echo nm_pagination( $total_pages, $current ); ?>

                </div>

            <?php else : ?>

                <div class="border-4 border-pop-black p-12 text-center bg-white shadow-hard-md">
                    <div class="w-20 h-20 bg-pop-yellow border-4 border-pop-black flex items-center justify-center mx-auto mb-6">
                        <i class="fa fa-search text-3xl" aria-hidden="true"></i>
                    </div>
                    <h2 class="font-sans font-black text-3xl uppercase mb-4">
                        <?php esc_html_e( 'Nothing Found', 'neonmagic' ); ?>
                    </h2>
                    <p class="font-mono font-medium mb-8">
                        <?php
                        if ( is_search() ) {
                            printf( esc_html__( 'No results for "%s". Try a different search.', 'neonmagic' ), get_search_query() );
                        } else {
                            esc_html_e( 'No files have been published yet.', 'neonmagic' );
                        }
                        ?>
                    </p>
                    <?php get_search_form(); ?>
                </div>

            <?php endif; ?>

        </main>
    </div>
</div>

<?php get_footer(); ?>
