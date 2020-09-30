<?php

/**
 * Change [Select options] for variable product
 */
add_filter( 'woocommerce_product_add_to_cart_text', function( $text ) {
  global $product;
  if ( $product->is_type( 'variable' ) ) {
    $text = $product->is_purchasable() ? __( 'CLICK TO VIEW MORE OPTIONS', 'woocommerce' ) : __( 'READ MORE', 'woocommerce' );
  }
  return $text;
}, 10 );
