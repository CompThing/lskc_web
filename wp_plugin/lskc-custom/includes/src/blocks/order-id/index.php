<?php
defined( 'ABSPATH' ) || exit;

register_block_type( __DIR__, [
    'render_callback' => function( $attrs, $content, $block ) {
        $order_id = $block->context['lskc-custom/orderId'] ?? null;

        if ( ! $order_id ) {
            return '';
        }

        return esc_html( $order_id );
    }
] );
