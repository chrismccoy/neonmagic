<?php
/**
 * NeonMagic — Sidebar Template
 *
 * @package NeonMagic
 */
?>
<aside class="w-full md:w-[280px] flex-shrink-0" role="complementary" aria-label="<?php esc_attr_e( 'Sidebar', 'neonmagic' ); ?>">
    <div class="sticky top-28 space-y-8">

        <div>
            <div class="relative mb-6">
                <div class="absolute inset-0 bg-pop-black translate-x-2 translate-y-2"></div>
                <div class="relative bg-white border-4 border-pop-black p-3 text-center">
                    <h2 class="font-sans font-black text-2xl uppercase"><?php esc_html_e( 'File Types', 'neonmagic' ); ?></h2>
                </div>
            </div>

            <ul class="space-y-3 font-mono font-bold" role="list">
                <?php
                $categories = get_categories( [
                    'orderby'    => 'count',
                    'order'      => 'DESC',
                    'hide_empty' => true,
                    'number'     => 20,
                ] );

                foreach ( $categories as $category ) :
                    $is_current = is_category( $category->term_id );
                    $bg         = $is_current ? 'bg-pop-yellow translate-x-1 -translate-y-1 shadow-[4px_4px_0_0_#000]' : 'bg-white';
                    ?>
                    <li>
                        <a href="<?php echo esc_url( get_category_link( $category->term_id ) ); ?>"
                           class="flex justify-between items-center group <?php echo $bg; ?> border-2 border-pop-black p-2 hover:bg-pop-yellow hover:translate-x-1 hover:-translate-y-1 transition-all shadow-[2px_2px_0_0_#000] hover:shadow-[4px_4px_0_0_#000]"
                           <?php echo $is_current ? 'aria-current="page"' : ''; ?>>
                            <span><?php echo esc_html( $category->name ); ?></span>
                            <span class="bg-pop-black text-pop-white text-xs px-2 py-1">
                                <?php echo esc_html( $category->count ); ?>
                            </span>
                        </a>
                    </li>
                <?php endforeach; ?>

                <?php if ( empty( $categories ) ) : ?>
                    <li class="text-sm text-gray-500 p-2"><?php esc_html_e( 'No categories yet.', 'neonmagic' ); ?></li>
                <?php endif; ?>
            </ul>
        </div>

        <div>
            <div class="relative mb-6">
                <div class="absolute inset-0 bg-pop-black translate-x-2 translate-y-2"></div>
                <div class="relative bg-pop-cyan border-4 border-pop-black p-3 text-center">
                    <h2 class="font-sans font-black text-xl uppercase"><?php esc_html_e( 'Recent Uploads', 'neonmagic' ); ?></h2>
                </div>
            </div>

            <div class="space-y-3">
                <?php
                $recent_posts = new WP_Query( [
                    'posts_per_page'      => 5,
                    'post_status'         => 'publish',
                    'no_found_rows'       => true,
                    'ignore_sticky_posts' => true,
                ] );

                while ( $recent_posts->have_posts() ) :
                    $recent_posts->the_post();
                    $file_type = nm_get_file_type();
                    $thumb_url = has_post_thumbnail()
                        ? get_the_post_thumbnail_url( null, 'neonmagic-thumb' )
                        : 'https://placehold.co/64x48/5CE1E6/000000?text=FILE&font=roboto';
                    ?>
                    <a href="<?php the_permalink(); ?>"
                       class="flex gap-3 items-start bg-white border-2 border-pop-black p-2 hover:bg-pop-yellow transition-colors shadow-[2px_2px_0_0_#000] group">
                        <div class="w-16 h-12 border-2 border-black flex-shrink-0 overflow-hidden relative">
                            <img src="<?php echo esc_url( $thumb_url ); ?>"
                                 alt="<?php the_title_attribute(); ?>"
                                 class="w-full h-full object-cover group-hover:scale-110 transition-transform" />
                            <?php if ( $file_type ) : ?>
                                <span class="absolute bottom-0 right-0 bg-pop-black text-white font-mono font-bold leading-none px-1"
                                      style="font-size:8px">.<?php echo esc_html( $file_type ); ?></span>
                            <?php endif; ?>
                        </div>
                        <div>
                            <p class="font-mono font-bold text-xs leading-tight line-clamp-2">
                                <?php the_title(); ?>
                            </p>
                            <p class="font-mono text-xs text-gray-400 mt-1 flex items-center gap-1">
                                <i class="fa fa-calendar-alt" aria-hidden="true"></i>
                                <?php echo esc_html( get_the_date( 'M d, Y' ) ); ?>
                            </p>
                        </div>
                    </a>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>

        <?php
        $tags = get_tags( [ 'hide_empty' => true, 'number' => 20, 'orderby' => 'count', 'order' => 'DESC' ] );
        if ( $tags ) :
        ?>
        <div>
            <div class="relative mb-6">
                <div class="absolute inset-0 bg-pop-black translate-x-2 translate-y-2"></div>
                <div class="relative bg-pop-pink border-4 border-pop-black p-3 text-center">
                    <h2 class="font-sans font-black text-xl uppercase"><?php esc_html_e( 'Tags', 'neonmagic' ); ?></h2>
                </div>
            </div>

            <div class="flex flex-wrap gap-2" role="list">
                <?php foreach ( $tags as $tag ) : ?>
                    <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"
                       class="px-3 py-1 bg-white border-2 border-pop-black font-mono font-bold text-xs uppercase hover:bg-pop-yellow hover:-translate-y-0.5 transition-all shadow-[2px_2px_0_0_#000] <?php echo is_tag( $tag->term_id ) ? 'bg-pop-yellow' : ''; ?>"
                       role="listitem">
                        #<?php echo esc_html( $tag->name ); ?>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <?php if ( is_active_sidebar( 'sidebar-1' ) ) :
            dynamic_sidebar( 'sidebar-1' );
        endif; ?>

    </div>
</aside>
