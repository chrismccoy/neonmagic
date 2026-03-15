<?php
/**
 * NeonMagic — Header Template
 *
 * @package NeonMagic
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <?php wp_head(); ?>
</head>

<body <?php body_class( 'bg-pop-cyan text-pop-black font-mono min-h-screen p-2 md:p-8 flex flex-col items-center' ); ?>>
<?php wp_body_open(); ?>

<div class="w-full max-w-[1400px] bg-white border-4 border-pop-black shadow-hard-xl relative flex flex-col">

    <header class="border-b-4 border-pop-black p-4 md:p-6 flex flex-col md:flex-row gap-6 justify-between items-center bg-white sticky top-0 z-40" role="banner">

        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
           class="group relative inline-block transform -rotate-1 hover:rotate-0 transition-transform"
           aria-label="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
            <div class="absolute inset-0 bg-pop-black translate-x-1 translate-y-1 group-hover:translate-x-2 group-hover:translate-y-2 transition-transform"></div>
            <div class="relative bg-pop-yellow border-2 border-pop-black px-4 py-2 flex items-center gap-3">
                <?php if ( has_custom_logo() ) :
                    the_custom_logo();
                else : ?>
                    <span class="w-10 h-10 bg-black text-white rounded-full flex items-center justify-center font-black text-xl" aria-hidden="true">
                        <?php echo esc_html( strtolower( substr( get_bloginfo( 'name' ), 0, 2 ) ) ); ?>
                    </span>
                    <span class="font-sans font-black text-2xl uppercase tracking-tighter italic">
                        <?php bloginfo( 'name' ); ?>
                    </span>
                <?php endif; ?>
            </div>
        </a>

        <div class="flex-grow w-full md:w-auto md:max-w-xl">
            <?php get_search_form(); ?>
        </div>

        <button id="mobile-menu-btn"
                class="md:hidden btn-physics h-12 w-12 bg-pop-pink border-2 border-pop-black shadow-hard-sm flex items-center justify-center text-2xl"
                aria-label="<?php esc_attr_e( 'Open menu', 'neonmagic' ); ?>"
                aria-expanded="false"
                aria-controls="mobile-menu">
            <i class="fa fa-bars" aria-hidden="true"></i>
        </button>
    </header>

    <nav class="hidden md:flex border-b-4 border-pop-black bg-white p-4 justify-center" role="navigation" aria-label="<?php esc_attr_e( 'Primary', 'neonmagic' ); ?>" id="desktop-nav">
        <?php
        if ( has_nav_menu( 'primary' ) ) {
            wp_nav_menu( [
                'theme_location' => 'primary',
                'container'      => false,
                'menu_class'     => 'flex flex-wrap gap-4 md:gap-6',
                'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                'walker'         => new NeonMagic_Nav_Walker(),
            ] );
        } else {
            // Fallback nav with home + categories
            echo '<ul class="flex flex-wrap gap-4 md:gap-6">';
            echo '<li><a href="' . esc_url( home_url( '/' ) ) . '" class="inline-block px-6 py-3 bg-pop-cyan border-2 border-pop-black shadow-hard-sm font-bold uppercase hover:-translate-y-1 hover:shadow-hard-md transition-all">' . esc_html__( 'Downloads', 'neonmagic' ) . '</a></li>';
            $cats = get_categories( [ 'number' => 5, 'hide_empty' => true ] );
            foreach ( $cats as $cat ) {
                echo '<li><a href="' . esc_url( get_category_link( $cat->term_id ) ) . '" class="inline-block px-6 py-3 bg-white border-2 border-pop-black shadow-hard-sm font-bold uppercase hover:bg-pop-yellow hover:-translate-y-1 hover:shadow-hard-md transition-all">' . esc_html( $cat->name ) . '</a></li>';
            }
            echo '</ul>';
        }
        ?>
    </nav>
