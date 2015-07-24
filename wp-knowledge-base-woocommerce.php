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

class WP_Knowledge_Base_WooCommerce {

	/**
	 * Constructor
	 */
	public function __construct() {
		require_once( 'classes/class-breadcrumbs.php' );
		$this->hooks();
	}

	/**
	 * Hooks
	 */
	private function hooks() {
		if ( ! is_admin() ) {
			$breadcrumbs = new WPKBWC_Breadcrumbs();
			add_action( 'wp', array( $breadcrumbs, 'fix_breadcrumbs' ) );
		}
	}
}

function __wp_kb_wc_init() {
	new WP_Knowledge_Base_WooCommerce();
}

add_action( 'plugins_loaded', '__wp_kb_wc_init', 20 );