<?php
/**
 * NeonMagic — Custom Nav Walkers
 *
 * @package NeonMagic
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Footer Walker
if ( ! class_exists( 'NeonMagic_Footer_Nav_Walker' ) ) :
class NeonMagic_Footer_Nav_Walker extends Walker_Nav_Menu {
    public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
        $item       = $data_object;
        $atts       = [ 'href' => $item->url ?? '#' ];
        $attributes = '';
        foreach ( $atts as $attr => $val ) {
            if ( $val ) $attributes .= ' ' . $attr . '="' . esc_attr( $val ) . '"';
        }
        $output .= '<li><a' . $attributes . ' class="font-mono text-gray-300 hover:text-pop-yellow transition-colors text-sm">' . esc_html( $item->title ) . '</a></li>';
    }
    public function end_el( &$output, $data_object, $depth = 0, $args = null ) {}
}
endif;

// Mobile Nav Walker
if ( ! class_exists( 'NeonMagic_Mobile_Nav_Walker' ) ) :
class NeonMagic_Mobile_Nav_Walker extends Walker_Nav_Menu {
    private static $mobile_colors = [ 'bg-pop-cyan', 'bg-pop-yellow', 'bg-pop-pink', 'bg-white' ];
    private static $mobile_idx    = 0;

    public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
        $item  = $data_object;
        $color = self::$mobile_colors[ self::$mobile_idx % count( self::$mobile_colors ) ];
        self::$mobile_idx++;
        $atts       = [ 'href' => $item->url ?? '#' ];
        $attributes = '';
        foreach ( $atts as $attr => $val ) {
            if ( $val ) $attributes .= ' ' . $attr . '="' . esc_attr( $val ) . '"';
        }
        $output .= '<a' . $attributes . ' class="block px-6 py-4 ' . $color . ' border-4 border-pop-black font-sans font-black text-xl uppercase shadow-hard-sm hover:-translate-y-1 hover:shadow-hard-md transition-all">';
        $output .= esc_html( $item->title );
        $output .= '</a>';
    }
    public function end_el( &$output, $data_object, $depth = 0, $args = null ) {}
}
endif;

if ( ! class_exists( 'NeonMagic_Nav_Walker' ) ) :
class NeonMagic_Nav_Walker extends Walker_Nav_Menu {

    /** @var array Cycle through accent colours */
    private static $colors = [
        'hover:bg-pop-yellow',
        'hover:bg-pop-cyan',
        'hover:bg-pop-pink',
        'hover:bg-pop-yellow',
    ];
    private static $idx = 0;

    public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
        $item   = $data_object;
        $color  = self::$colors[ self::$idx % count( self::$colors ) ];
        self::$idx++;

        $is_current = in_array( 'current-menu-item', $item->classes, true )
                   || in_array( 'current-menu-ancestor', $item->classes, true );
        $bg = $is_current ? 'bg-pop-cyan' : 'bg-white';

        $atts           = [];
        $atts['href']   = ! empty( $item->url ) ? $item->url : '';
        $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
        $atts['target'] = ! empty( $item->target ) ? $item->target : '';
        $atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';

        $attributes = '';
        foreach ( $atts as $attr => $val ) {
            if ( $val ) {
                $attributes .= ' ' . $attr . '="' . esc_attr( $val ) . '"';
            }
        }

        $title   = apply_filters( 'the_title', $item->title, $item->ID );
        $output .= '<li>';
        $output .= '<a' . $attributes . ' class="inline-block px-6 py-3 ' . $bg . ' border-2 border-pop-black shadow-hard-sm font-bold uppercase ' . $color . ' hover:-translate-y-1 hover:shadow-hard-md transition-all">';
        $output .= esc_html( $title );
        $output .= '</a>';
    }

    public function end_el( &$output, $data_object, $depth = 0, $args = null ) {
        $output .= '</li>';
    }
}
endif;
