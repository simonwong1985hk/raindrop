<?php

/**
 * Change [Select options] for variable product
 */
add_filter( 'woocommerce_product_add_to_cart_text' , 'custom_woocommerce_product_add_to_cart_text' );
function custom_woocommerce_product_add_to_cart_text() {
    global $product;
    $product_type = $product->product_type;
    switch ( $product_type ) {
        case 'variable':
            return __( 'Click to view more options', 'woocommerce' );
            break;
    }
}
