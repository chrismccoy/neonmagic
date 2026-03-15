<?php
/**
 * NeonMagic — Content Card Template Part
 *
 * @package NeonMagic
 */

$post_id      = get_the_ID();
$is_paid      = nm_is_paid( $post_id );
$file_type    = nm_get_file_type( $post_id );
$file_size    = nm_get_file_size( $post_id );
$compat       = nm_get_compatibility( $post_id );
$price        = nm_get_price( $post_id );
$buy_url      = nm_get_buy_url( $post_id );
$post_url     = get_the_permalink();

$thumb_url = has_post_thumbnail() ? get_the_post_thumbnail_url( $post_id, 'neonmagic-card' ) : 'https://placehold.co/400x300/5CE1E6/000000?text=' . rawurlencode( strtoupper( $file_type ?: 'FILE' ) ) . '&font=roboto';
?>
<article class="flex flex-col h-full bg-white border-4 border-pop-black shadow-hard-sm hover:shadow-hard-xl hover:-translate-y-2 transition-all duration-200"
         data-post-id="<?php echo esc_attr( $post_id ); ?>">

    <a href="<?php echo esc_url( $post_url ); ?>"
       class="relative block overflow-hidden border-b-4 border-pop-black aspect-[4/3] group"
       tabindex="-1"
       aria-label="<?php the_title_attribute(); ?>">

        <img src="<?php echo esc_url( $thumb_url ); ?>"
             alt="<?php the_title_attribute(); ?>"
             loading="lazy"
             class="w-full h-full object-cover transition-all duration-300 group-hover:scale-110" />

        <?php if ( $file_type ) : ?>
            <span class="absolute top-2 left-2 bg-pop-black text-white font-mono font-bold text-xs px-2 py-1 border border-white">
                .<?php echo esc_html( $file_type ); ?>
            </span>
        <?php endif; ?>

        <?php if ( ! $is_paid ) : ?>
            <span class="absolute top-2 right-2 bg-pop-cyan border-2 border-black font-sans font-black text-xs px-2 py-1 uppercase">
                <?php esc_html_e( 'FREE', 'neonmagic' ); ?>
            </span>
        <?php endif; ?>

        <span class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity" aria-hidden="true">
            <span class="bg-pop-pink border-2 border-black w-12 h-12 flex items-center justify-center shadow-hard-sm">
                <i class="fa fa-eye text-lg"></i>
            </span>
        </span>
    </a>

    <div class="p-4 flex flex-col flex-grow">

        <div class="mb-2">
            <span class="font-mono text-xs text-gray-500 flex items-center gap-1 flex-wrap">
                <?php if ( $compat ) : ?>
                    <i class="fa fa-puzzle-piece" aria-hidden="true"></i>
                    <?php echo esc_html( $compat ); ?>
                <?php endif; ?>
                <?php if ( $file_size ) : ?>
                    <span class="ml-auto text-gray-400">
                        <i class="fa fa-hdd" aria-hidden="true"></i>
                        <?php echo esc_html( $file_size ); ?>
                    </span>
                <?php endif; ?>
            </span>
        </div>

        <h2 class="font-sans font-black text-xl uppercase leading-tight mb-3">
            <a href="<?php echo esc_url( $post_url ); ?>"
               class="hover:underline decoration-4 decoration-pop-cyan">
                <?php the_title(); ?>
            </a>
        </h2>

        <div class="mt-auto pt-4 border-t-2 border-dashed border-gray-300 flex items-center justify-between">

            <?php if ( $is_paid && $price ) : ?>
                <div class="font-mono font-bold text-2xl flex items-start">
                    <span class="text-sm mt-1 mr-0.5" aria-hidden="true">$</span>
                    <span><?php echo esc_html( $price ); ?></span>
                </div>
            <?php else : ?>
                <div class="flex items-center gap-1">
                    <span class="bg-pop-cyan border-2 border-pop-black px-3 py-1 font-sans font-black text-sm uppercase shadow-[2px_2px_0_0_#000]">
                        <?php esc_html_e( 'FREE', 'neonmagic' ); ?>
                    </span>
                </div>
            <?php endif; ?>

            <?php if ( $is_paid && $buy_url ) : ?>
                <a href="<?php echo esc_url( $buy_url ); ?>"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="btn-physics bg-pop-black text-white px-4 py-2 text-xs font-bold uppercase tracking-widest border-2 border-pop-black hover:bg-pop-pink hover:text-black flex items-center gap-1 shadow-hard-sm">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                    <?php esc_html_e( 'Buy', 'neonmagic' ); ?>
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url( $post_url ); ?>"
                   class="btn-physics bg-pop-cyan text-black px-4 py-2 text-xs font-bold uppercase tracking-widest border-2 border-pop-black hover:bg-pop-yellow flex items-center gap-1 shadow-hard-sm">
                    <i class="fa fa-download" aria-hidden="true"></i>
                    <?php esc_html_e( 'Get', 'neonmagic' ); ?>
                </a>
            <?php endif; ?>

        </div>
    </div>
</article>
