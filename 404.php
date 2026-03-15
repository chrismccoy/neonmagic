<?php
/**
 * NeonMagic — 404 Not Found Template
 *
 * @package NeonMagic
 */

get_header();
?>

<div class="p-6 md:p-8 bg-gray-50 flex-grow flex items-center justify-center min-h-[60vh]">
    <div class="text-center max-w-lg w-full">

        <div class="relative inline-block mb-8">
            <div class="absolute inset-0 bg-pop-black translate-x-4 translate-y-4"></div>
            <div class="relative bg-pop-yellow border-4 border-pop-black px-12 py-8">
                <span class="font-sans font-black text-9xl leading-none">404</span>
            </div>
        </div>

        <h1 class="font-sans font-black text-4xl uppercase mb-4 tracking-tight">
            <?php esc_html_e( 'File Not Found', 'neonmagic' ); ?>
        </h1>
        <p class="font-mono font-medium mb-8 text-gray-600">
            <?php esc_html_e( 'The file you were looking for has been deleted, moved, or never existed. Classic.', 'neonmagic' ); ?>
        </p>

        <div class="mb-8">
            <?php get_search_form(); ?>
        </div>

        <div class="relative inline-block">
            <div class="absolute inset-0 bg-pop-black translate-x-2 translate-y-2"></div>
            <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
               class="relative inline-flex items-center gap-3 bg-pop-cyan border-4 border-pop-black px-8 py-4 font-sans font-black text-xl uppercase hover:bg-pop-yellow transition-colors shadow-hard-md">
                <i class="fa fa-arrow-left" aria-hidden="true"></i>
                <?php esc_html_e( 'Back to Downloads', 'neonmagic' ); ?>
            </a>
        </div>

    </div>
</div>

<?php get_footer(); ?>
