<?php
register_block_type( 'lskc-custom/order-id', [
    'render_callback' => function () {
        $order = wc_oql_get_current_order();
        return $order ? esc_html( $order->get_id() ) : '';
    }
]);
