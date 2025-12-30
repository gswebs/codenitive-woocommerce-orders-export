<?php
namespace WooJMBExporter;

defined('ABSPATH') || exit;

class JMB_Order_Exporter_Admin_Assets {

    public static function init() {
        add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue' ] );
    }

    public static function enqueue( $hook ) {

        // Debug (temporary)
        // error_log( $hook );

        if ( $hook !== 'woocommerce_page_export-orders-data' ) {
            return;
        }

        wp_enqueue_style(
            'jmb-order-export-admin-css',
            plugin_dir_url( dirname(__FILE__) ) . 'assets/css/admin-style.css',
            [],
            '0.1.1'
        );
    }
}

JMB_Order_Exporter_Admin_Assets::init();
