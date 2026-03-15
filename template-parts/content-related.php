<?php
/**
 * NeonMagic — Related File Card Template Part
 *
 * @package NeonMagic
 */

$post_id   = get_the_ID();
$is_paid   = nm_is_paid( $post_id );
$file_type = nm_get_file_type( $post_id );
$file_size = nm_get_file_size( $post_id );
$compat    = nm_get_compatibility( $post_id );
$price     = nm_get_price( $post_id );
$buy_url   = nm_get_buy_url( $post_id );
$post_url  = get_the_permalink();

$thumb_url = has_post_thumbnail() ? get_the_post_thumbnail_url( $post_id, 'neonmagic-related' ) : 'https://placehold.co/400x300/FF90E8/000000?text=' . rawurlencode( strtoupper( $file_type ?: 'FILE' ) ) . '&font=roboto';
?>
<article class="flex flex-col h-full bg-white border-4 border-pop-black shadow-hard-sm hover:shadow-hard-xl hover:-translate-y-2 transition-all duration-200">

    <a href="<?php echo esc_url( $post_url ); ?>"
       class="relative block overflow-hidden border-b-4 border-pop-black aspect-[4/3] group"
       tabindex="-1">
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
    </a>

    <div class="p-4 flex flex-col flex-grow">
        <span class="font-mono text-xs text-gray-500 mb-2 flex items-center gap-2">
            <?php if ( $compat ) : ?>
                <i class="fa fa-puzzle-piece" aria-hidden="true"></i>
                <?php echo esc_html( $compat ); ?>
            <?php endif; ?>
            <?php if ( $file_size ) : ?>
                <span class="ml-auto">
                    <i class="fa fa-hdd" aria-hidden="true"></i>
                    <?php echo esc_html( $file_size ); ?>
                </span>
            <?php endif; ?>
        </span>

        <h3 class="font-sans font-black text-lg uppercase leading-tight mb-3">
            <a href="<?php echo esc_url( $post_url ); ?>"
               class="hover:underline decoration-4 decoration-pop-cyan">
                <?php the_title(); ?>
            </a>
        </h3>

        <div class="mt-auto pt-3 border-t-2 border-dashed border-gray-300">
            <?php if ( $is_paid && $buy_url ) : ?>
                <a href="<?php echo esc_url( $buy_url ); ?>"
                   target="_blank"
                   rel="noopener noreferrer"
                   class="btn-physics inline-flex items-center gap-2 bg-pop-black text-white px-4 py-2 text-xs font-bold uppercase tracking-widest border-2 border-pop-black hover:bg-pop-pink hover:text-black shadow-hard-sm">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                    <?php echo $price ? '$' . esc_html( $price ) : esc_html__( 'Buy', 'neonmagic' ); ?>
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url( $post_url ); ?>"
                   class="btn-physics inline-flex items-center gap-2 bg-pop-cyan text-black px-4 py-2 text-xs font-bold uppercase tracking-widest border-2 border-pop-black hover:bg-pop-yellow shadow-hard-sm">
                    <i class="fa fa-download" aria-hidden="true"></i>
                    <?php esc_html_e( 'Get Free', 'neonmagic' ); ?>
                </a>
            <?php endif; ?>
        </div>
    </div>
</article>
