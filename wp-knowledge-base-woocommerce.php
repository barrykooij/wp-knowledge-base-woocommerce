<?php

/*
 Plugin Name: WP Knowledge Base - WooCommerce
 Plugin URI: http://www.barrykooij.com/
 Description: Lets WP Knowledge Base and WooCommerce play along nicely
 Version: 1.0.0
 Author: Never5
 Author URI: http://www.never5.com/
 License: GPL v2
*/

namespace WPKB\WooCommerce;

class WP_Knowledge_Base_WooCommerce {

	/**
	 * Constructor
	 */
	public function __construct() {
		require_once( 'classes/Breadcrumbs.php' );
		require_once( 'classes/MetaBox.php' );
		require_once( 'classes/Shortcode.php' );
		$this->hooks();
	}

	/**
	 * Hooks
	 */
	private function hooks() {

		// frontend only
		if ( ! is_admin() ) {

			// breadcrumbs
			$breadcrumbs = new Breadcrumbs();
			add_action( 'wp', array( $breadcrumbs, 'handleHooks' ) );

			// setup shortcode on frontend
			$shortcode = new Shortcode();
			$shortcode->setup();

		}

		// setup product link
		$productLink = new MetaBox();
		$productLink->add_hooks();

	}
}

function __wp_kb_wc_init() {
	new WP_Knowledge_Base_WooCommerce();
}

add_action( 'plugins_loaded', '\WPKB\WooCommerce\__wp_kb_wc_init', 20 );