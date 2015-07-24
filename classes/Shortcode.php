<?php

namespace WPKB\WooCommerce;

class Shortcode {

	/**
	 * Hooks etc.
	 */
	public function setup() {
		add_shortcode( 'wpkb-woocommerce-button', array( $this, 'output' ) );
	}

	/**
	 * Output shortcode
	 *
	 * @todo remove DLM specific styling, waiting for TemplateManager changes.
	 */
	public function output() {
		$articleID = absint( get_the_ID() );
		if ( $articleID > 0 ) {
			$productID = absint( get_post_meta( $articleID, 'wpkb-woocommerce-product', true ) );
			if ( $productID > 0 ) {
				$product_url = get_permalink( $productID );
				if ( '' !== $product_url ) {
					/**
					 * This is very Download Monitor specific now because we can't do proper template loading yet
					 * @todo 'Get Extension' -> 'Get Product'
					 * @todo Remove wrapper div
					 */
					?>
					<div class="sidebar-doc-block">
						<a href="<?php echo $product_url; ?>" class="button wpkb-woocommerce-product-button" title="<?php echo get_the_title(); ?>"><?php _e( 'Get Extension', 'wpkb-woocommerce' ); ?></a>
					</div>

					<?php
				}
			}
		}
	}

}