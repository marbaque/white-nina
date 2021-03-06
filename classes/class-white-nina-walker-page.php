<?php
/**
 * Custom page walker for this theme.
 *
 * @package WordPress
 * @subpackage White Nina
 * @since Twenty Twenty 1.0
 */

if ( ! class_exists( 'WhiteNina_Walker_Page' ) ) {
	/**
	 * CUSTOM PAGE WALKER
	 * A custom walker for pages.
	 *
	 * @since Twenty Twenty 1.0
	 */
	class WhiteNina_Walker_Page extends Walker_Page {


		public function start_el( &$output, $page, $depth = 0, $args = array(), $current_page = 0 ) {

			if ( isset( $args['item_spacing'] ) && 'preserve' === $args['item_spacing'] ) {
				$t = "\t";
				$n = "\n";
			} else {
				$t = '';
				$n = '';
			}
			if ( $depth ) {
				$indent = str_repeat( $t, $depth );
			} else {
				$indent = '';
			}

			$css_class = array( 'page_item', 'page-item-' . $page->ID );

			if ( isset( $args['pages_with_children'][ $page->ID ] ) ) {
				$css_class[] = 'page_item_has_children';
			}

			if ( ! empty( $current_page ) ) {
				$_current_page = get_post( $current_page );
				if ( $_current_page && in_array( $page->ID, $_current_page->ancestors, true ) ) {
					$css_class[] = 'current_page_ancestor';
				}
				if ( $page->ID === $current_page ) {
					$css_class[] = 'current_page_item';
				} elseif ( $_current_page && $page->ID === $_current_page->post_parent ) {
					$css_class[] = 'current_page_parent';
				}
			} elseif ( get_option( 'page_for_posts' ) === $page->ID ) {
				$css_class[] = 'current_page_parent';
			}

			/** This filter is documented in wp-includes/class-walker-page.php */
			$css_classes = implode( ' ', apply_filters( 'page_css_class', $css_class, $page, $depth, $args, $current_page ) );
			$css_classes = $css_classes ? ' class="' . esc_attr( $css_classes ) . '"' : '';

			if ( '' === $page->post_title ) {
				/* translators: %d: ID of a post. */
				$page->post_title = sprintf( __( '#%d (no title)', 'white-nina' ), $page->ID );
			}

			$args['link_before'] = empty( $args['link_before'] ) ? '' : $args['link_before'];
			$args['link_after']  = empty( $args['link_after'] ) ? '' : $args['link_after'];

			$atts                 = array();
			$atts['href']         = get_permalink( $page->ID );
			$atts['aria-current'] = ( $page->ID === $current_page ) ? 'page' : '';

			/** This filter is documented in wp-includes/class-walker-page.php */
			$atts = apply_filters( 'page_menu_link_attributes', $atts, $page, $depth, $args, $current_page );

			$attributes = '';
			foreach ( $atts as $attr => $value ) {
				if ( ! empty( $value ) ) {
					$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
					$attributes .= ' ' . $attr . '="' . $value . '"';
				}
			}

			$args['list_item_before'] = '';
			$args['list_item_after']  = '';

			// Wrap the link in a div and append a sub menu toggle.
			if ( isset( $args['show_toggles'] ) && true === $args['show_toggles'] ) {
				// Wrap the menu item link contents in a div, used for positioning.
				$args['list_item_before'] = '<div class="ancestor-wrapper">';
				$args['list_item_after']  = '';

				// Add a toggle to items with children.
				if ( isset( $args['pages_with_children'][ $page->ID ] ) ) {

					$toggle_target_string = '.menu-modal .page-item-' . $page->ID . ' > ul';
					$toggle_duration      = white_nina_toggle_duration();

					// Add the sub menu toggle.
					$args['list_item_after'] .= '<button class="toggle sub-menu-toggle fill-children-current-color" data-toggle-target="' . $toggle_target_string . '" data-toggle-type="slidetoggle" data-toggle-duration="' . absint( $toggle_duration ) . '" aria-expanded="false"><span class="screen-reader-text">' . __( 'Show sub menu', 'white-nina' ) . '</span>' . '<svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="plus" class="svg-inline--fa fa-plus fa-w-12" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M376 232H216V72c0-4.42-3.58-8-8-8h-32c-4.42 0-8 3.58-8 8v160H8c-4.42 0-8 3.58-8 8v32c0 4.42 3.58 8 8 8h160v160c0 4.42 3.58 8 8 8h32c4.42 0 8-3.58 8-8V280h160c4.42 0 8-3.58 8-8v-32c0-4.42-3.58-8-8-8z"></path></svg>' . '</button>';

				}

				// Close the wrapper.
				$args['list_item_after'] .= '</div><!-- .ancestor-wrapper -->';
			}

			// Add icons to menu items with children.
			if ( isset( $args['show_sub_menu_icons'] ) && true === $args['show_sub_menu_icons'] ) {
				if ( isset( $args['pages_with_children'][ $page->ID ] ) ) {
					$args['list_item_after'] = '<span class="icon"><svg aria-hidden="true" focusable="false" data-prefix="fal" data-icon="plus" class="svg-inline--fa fa-plus fa-w-12" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 384 512"><path fill="currentColor" d="M376 232H216V72c0-4.42-3.58-8-8-8h-32c-4.42 0-8 3.58-8 8v160H8c-4.42 0-8 3.58-8 8v32c0 4.42 3.58 8 8 8h160v160c0 4.42 3.58 8 8 8h32c4.42 0 8-3.58 8-8V280h160c4.42 0 8-3.58 8-8v-32c0-4.42-3.58-8-8-8z"></path></svg></span>';
				}
			}

			$output .= $indent . sprintf(
				'<li%s>%s<a%s>%s%s%s</a>%s',
				$css_classes,
				$args['list_item_before'],
				$attributes,
				$args['link_before'],
				/** This filter is documented in wp-includes/post-template.php */
				apply_filters( 'the_title', $page->post_title, $page->ID ),
				$args['link_after'],
				$args['list_item_after']
			);

			if ( ! empty( $args['show_date'] ) ) {
				if ( 'modified' === $args['show_date'] ) {
					$time = $page->post_modified;
				} else {
					$time = $page->post_date;
				}

				$date_format = empty( $args['date_format'] ) ? '' : $args['date_format'];
				$output     .= ' ' . mysql2date( $date_format, $time );
			}
		}
	}
}
