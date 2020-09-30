<?php

/**
 * Change [Select options] for variable product
 */
add_filter( 'woocommerce_product_add_to_cart_text', function ( $text ) {
  global $product;
  if ( $product->is_type( 'variable' ) ) {
    $text = $product->is_purchasable() ? __( 'CUSTOM TEXT', 'woocommerce' ) : __( 'CUSTOM TEXT', 'woocommerce' );
  }
  return $text;
}, 10 );

/**
 * Change [Sale!] for on-sale product
 */
add_filter( 'woocommerce_sale_flash', function ( $html ) {
	return str_replace( __( 'Sale!', 'woocommerce' ), __( 'CUSTOM TEXT', 'woocommerce' ), $html );
} );
