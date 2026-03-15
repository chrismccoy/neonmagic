<?php
/**
 * NeonMagic — Footer Template
 *
 * @package NeonMagic
 */
?>

    <footer class="border-t-4 border-pop-black bg-pop-black text-white p-8 md:p-12 mt-auto" role="contentinfo">
        <div class="flex flex-col md:flex-row items-center justify-between gap-8">

            <div class="flex items-center gap-4">
                <span class="w-12 h-12 bg-pop-yellow text-black rounded-full flex items-center justify-center font-black text-xl border-2 border-white" aria-hidden="true">
                    <?php echo esc_html( strtolower( substr( get_bloginfo( 'name' ), 0, 2 ) ) ); ?>
                </span>
                <div class="font-sans font-black text-3xl uppercase">
                    <?php bloginfo( 'name' ); ?>
                </div>
            </div>

            <?php if ( has_nav_menu( 'footer' ) ) : ?>
                <nav aria-label="<?php esc_attr_e( 'Footer Navigation', 'neonmagic' ); ?>">
                    <?php
                    wp_nav_menu( [
                        'theme_location' => 'footer',
                        'container'      => false,
                        'menu_class'     => 'flex flex-wrap gap-4 justify-center',
                        'link_before'    => '',
                        'link_after'     => '',
                        'before'         => '',
                        'after'          => '',
                        'walker'         => new NeonMagic_Footer_Nav_Walker(),
                    ] );
                    ?>
                </nav>
            <?php endif; ?>

            <div class="font-mono text-gray-400 text-sm">
                &copy; <?php echo esc_html( date( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>
            </div>
        </div>
    </footer>

</div>

<div id="mobile-menu"
     class="fixed inset-0 z-50 bg-pop-yellow transform translate-x-full transition-transform duration-300 flex flex-col border-l-8 border-pop-black"
     aria-hidden="true"
     role="dialog"
     aria-modal="true"
     aria-label="<?php esc_attr_e( 'Mobile Navigation', 'neonmagic' ); ?>">

    <div class="p-6 flex justify-between items-center border-b-4 border-pop-black bg-white">
        <span class="font-sans font-black text-2xl uppercase"><?php esc_html_e( 'Menu', 'neonmagic' ); ?></span>
        <button id="close-menu"
                class="w-12 h-12 border-2 border-pop-black flex items-center justify-center hover:bg-pop-black hover:text-white transition-colors bg-pop-pink shadow-hard-sm active:translate-y-1 active:shadow-none"
                aria-label="<?php esc_attr_e( 'Close menu', 'neonmagic' ); ?>">
            <i class="fa fa-times text-xl" aria-hidden="true"></i>
        </button>
    </div>

    <nav class="p-6 flex flex-col gap-4 overflow-y-auto" id="mobile-nav-list" aria-label="<?php esc_attr_e( 'Mobile Navigation', 'neonmagic' ); ?>">
        <?php
        if ( has_nav_menu( 'primary' ) ) {
            wp_nav_menu( [
                'theme_location' => 'primary',
                'container'      => false,
                'items_wrap'     => '%3$s',
                'walker'         => new NeonMagic_Mobile_Nav_Walker(),
            ] );
        } else {
            echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="block px-6 py-4 bg-white border-4 border-pop-black font-sans font-black text-xl uppercase shadow-hard-sm hover:bg-pop-cyan transition-colors">' . esc_html__( 'Downloads', 'neonmagic' ) . '</a>';
            $cats = get_categories( [ 'number' => 8, 'hide_empty' => true ] );
            foreach ( $cats as $cat ) {
                echo '<a href="' . esc_url( get_category_link( $cat->term_id ) ) . '" class="block px-6 py-4 bg-white border-4 border-pop-black font-sans font-black text-xl uppercase shadow-hard-sm hover:bg-pop-yellow transition-colors">' . esc_html( $cat->name ) . '</a>';
            }
        }
        ?>
    </nav>
</div>

<button id="scrolltop"
        class="fixed bottom-6 right-6 z-40 bg-pop-cyan border-4 border-pop-black w-16 h-16 shadow-hard-md hidden flex-col items-center justify-center btn-physics group"
        aria-label="<?php esc_attr_e( 'Scroll to top', 'neonmagic' ); ?>">
    <i class="fa fa-arrow-up text-xl group-hover:-translate-y-1 transition-transform" aria-hidden="true"></i>
</button>

<?php wp_footer(); ?>
</body>
</html>
