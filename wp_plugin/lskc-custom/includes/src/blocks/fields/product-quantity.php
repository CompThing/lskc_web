<?php
register_block_type( 'wc/order-product-quantity', [
    'attributes' => [
        'productId' => ['type' => 'number']
    ],
    'render_callback' => function ( $attrs ) {

        $order = wc_oql_get_current_order();
        if ( ! $order || empty( $attrs['productId'] ) ) return '';

        $qty = 0;
        foreach ( $order->get_items() as $item ) {
            if ( $item->get_product_id() === (int) $attrs['productId'] ) {
                $qty += $item->get_quantity();
            }
        }

        return esc_html( $qty );
    }
]);
