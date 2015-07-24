<?php

namespace WPKB\WooCommerce;

class Breadcrumbs {

	/**
	 * Replace WooCommerce breadcrumbs with WP Knowledge Base breadcrumbs on WP Knowledge Base pages
	 *
	 * Hook this into 'wp'
	 */
	public function handleHooks() {
		if ( is_singular( 'wpkb-article' ) || is_tax( 'wpkb-category' ) ) {
			remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
			remove_action( 'storefront_content_top', 'woocommerce_breadcrumb', 10 );
			add_action( 'woocommerce_before_main_content', array( $this, 'output' ) );
			add_action( 'storefront_content_top', array( $this, 'output' ) );
		}
	}

	/**
	 * Output the new breadcrumbs
	 */
	public function output() {

		// get WooCommerce breadcrumbs args
		$args = wp_parse_args( array(), apply_filters( 'woocommerce_breadcrumb_defaults', array(
			'delimiter'   => '&nbsp;&#47;&nbsp;',
			'wrap_before' => '<nav class="woocommerce-breadcrumb" ' . ( is_single() ? 'itemprop="breadcrumb"' : '' ) . '>',
			'wrap_after'  => '</nav>',
			'before'      => '',
			'after'       => '',
			'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' )
		) ) );

		// build Crumbs
		$crumbs = new WPKB\Breadcrumbs\Crumbs( $GLOBALS['wpkb']->breadcrumbs->get_archive_page_id() );
		$crumbs->build_crumbs();

		// replace &nbsp; and trim because WP KB breadcrumbs already adds spaces
		$args['delimiter'] = trim( preg_replace( "/\s|&nbsp;/", ' ', $args['delimiter'] ) );

		// build prefix
		$prefix = '';
		if ( $args['home'] ) {
			$prefix .= '<a href="' . site_url( '/' ) . '">' . $args['home'] . '</a>';
		}
		if ( $kb_page_url = apply_filters( 'wp_kb_wc_page_url', false ) ) {

			if ( '' != $prefix ) {
				$prefix .= ' ' . $args['delimiter'] . ' ';
			}

			$prefix .= '<a href="' . $kb_page_url . '">Knowledge Base</a>';
		}


		echo $crumbs->build_html( '<nav class="woocommerce-breadcrumb">', '</nav>', $args['delimiter'], $prefix );
	}

}