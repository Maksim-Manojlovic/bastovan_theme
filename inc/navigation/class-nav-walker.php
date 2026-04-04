<?php
/**
 * Bastovan Nav Walker
 * Custom walker za WordPress navigaciju
 */

if ( ! class_exists( 'Bastovan_Nav_Walker' ) ) {

    class Bastovan_Nav_Walker extends Walker_Nav_Menu {

        public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
            $classes = empty( $item->classes ) ? [] : (array) $item->classes;

            // Dodaj CSS klase
            $class_names = implode( ' ', array_filter( $classes ) );

            $output .= '<li class="' . esc_attr( $class_names ) . '">';

            $atts = [];
            $atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
            $atts['target'] = ! empty( $item->target )     ? $item->target : '';
            $atts['rel']    = ! empty( $item->xfn )        ? $item->xfn : '';
            $atts['href']   = ! empty( $item->url )        ? $item->url : '';
            $atts['class']  = 'site-nav__link';

            if ( in_array( 'current-menu-item', $classes ) ) {
                $atts['class']        .= ' is-active';
                $atts['aria-current']  = 'page';
            }

            $atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

            $attributes = '';
            foreach ( $atts as $attr => $value ) {
                if ( ! empty( $value ) ) {
                    $attributes .= ' ' . $attr . '="' . esc_attr( $value ) . '"';
                }
            }

            $title = apply_filters( 'the_title', $item->title, $item->ID );
            $item_output  = $args->before ?? '';
            $item_output .= '<a' . $attributes . '>';
            $item_output .= ( $args->link_before ?? '' ) . $title . ( $args->link_after ?? '' );
            $item_output .= '</a>';
            $item_output .= $args->after ?? '';

            $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
        }
    }
}
