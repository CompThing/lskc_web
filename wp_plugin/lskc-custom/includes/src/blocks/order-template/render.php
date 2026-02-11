<?php
defined( 'ABSPATH' ) || exit;

function wc_oql_get_current_order() {
    return $GLOBALS['wc_oql_current_order'] ?? null;
}

register_block_type( __DIR__, [
    'render_callback' => function ( $attrs, $content, $block ) {

        if ( ! current_user_can( 'manage_woocommerce' ) ) {
            return '';
        }

        $query = $block->context['wc/orderQuery'] ?? null;
        if ( ! $query ) return '';

        $args = [
            'limit'   => $query['perPage'],
            'status'  => $query['statuses'],
            'orderby' => $query['orderBy'],
            'order'   => strtoupper( $query['order'] ),
            'return'  => 'objects',
        ];

        $orders = ( new WC_Order_Query( $args ) )->get_orders();

        if ( empty( $orders ) ) return '';

        ob_start();

        echo '<div class="wc-order-template">';

        foreach ( $orders as $order ) {
            $GLOBALS['wc_oql_current_order'] = $order;

            echo '<div class="wc-order">';
            echo do_blocks( $content );
            echo '</div>';
        }

        unset( $GLOBALS['wc_oql_current_order'] );

        echo '</div>';

        return ob_get_clean();
    }
]);
