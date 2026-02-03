register_block_type( __DIR__, [
    'attributes' => [
        'productId' => [
            'type' => 'number',
            'required' => true
        ]
    ],
    'render_callback' => function( $attrs, $content, $block ) {

        $order_id = $block->context['wc/orderId'] ?? null;
        if ( ! $order_id || empty( $attrs['productId'] ) ) {
            return '';
        }

        $order = wc_get_order( $order_id );
        if ( ! $order ) {
            return '';
        }

        $count = 0;
        foreach ( $order->get_items() as $item ) {
            if ( (int) $item->get_product_id() === (int) $attrs['productId'] ) {
                $count += $item->get_quantity();
            }
        }

        return esc_html( $count );
    }
] );
