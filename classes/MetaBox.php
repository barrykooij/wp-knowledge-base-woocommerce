<?php

namespace WPKB\WooCommerce;

class MetaBox {

	/**
	 * Add dem hooks
	 */
	public function add_hooks() {
		add_action( 'add_meta_boxes', array( $this, 'addMetaBox' ), 10, 1 );
		add_action( 'save_post', array( $this, 'saveMetaBoxData' ), 10, 2 );
	}

	/**
	 * Add meta box
	 */
	public function addMetaBox() {
		add_meta_box(
			'wpkb-woocommerce-product-link',
			__( 'WooCommerce Product', 'wpkb-woocommerce' ),
			array( $this, 'showMetaBox' ),
			'wpkb-article',
			'side'
		);
	}

	/**
	 * Meta box content
	 *
	 * @param \WP_Post $post
	 */
	public function showMetaBox( $post ) {
		// nonce
		wp_nonce_field( 'wpkb-woocommerce-2k23j23adasbo', 'wpkb-woocommerce-nonce' );

		// get products
		$products = get_posts( array( 'post_type' => 'product', 'posts_per_page' => - 1 ) );

		// current product
		$current = get_post_meta( $post->ID, 'wpkb-woocommerce-product', true );

		// loop, DOM, ad
		if ( count( $products ) > 0 ) {
			echo '<select name="wpkb-woocommerce-product" style="width: 100%">';
			echo '<option value="0">' . __( 'None', 'wpkb-woocommerce' ) . '</option>';
			foreach ( $products as $product ) {
				printf( '<option value="%s" ' . selected( $product->ID, $current, false ) . '>%s</option>', $product->ID, $product->post_title );
			}
			echo '</select>';
		} else {
			echo '<p>' . __( 'No WooCommerce products found!', 'wpkb-woocommerce' ) . '</p>';
		}
		?>
		<?php
	}

	/**
	 * Save meta box data
	 *
	 * @param int $post_id
	 * @param \WP_Post $post
	 */
	public function saveMetaBoxData( $post_id, $post ) {

		// nonce check #1
		if ( ! isset( $_POST['wpkb-woocommerce-nonce'] ) || ! isset( $_POST['wpkb-woocommerce-product'] ) ) {
			return;
		}

		// nonce check #2
		if ( ! wp_verify_nonce( $_POST['wpkb-woocommerce-nonce'], 'wpkb-woocommerce-2k23j23adasbo' ) ) {
			return;
		}

		// autosave check
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		// post type check
		if ( 'wpkb-article' != $post->post_type ) {
			return;
		}

		// capabilities
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		// set post meta
		$product_id = absint( $_POST['wpkb-woocommerce-product'] );
		update_post_meta( $post_id, 'wpkb-woocommerce-product', $product_id );

	}
}