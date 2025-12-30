## WooCommerce Orders Data Exporter By JMB

Contributors: Gurjit Singh  
Tags: woocommerce, email export, order exporter, csv export  
Requires at least: 5.6 
Tested up to: 6.8  
Requires PHP: 8.2  
License: GPLv2 or later  
License URI: https://www.gnu.org/licenses/gpl-2.0.html  

Export WooCommerce orders data details to CSV with filtering options. Supports filtering by date and order status.

### Description

**WooCommerce Orders Data Exporter By JMB** is a simple plugin to export WooCommerce orders data, like billing name, phone, order status, and order date.

**Key Features:**
- Export WooCommerce orders data
- Optional fields: Order Id, Name, Phone, Products, Order Status, Order Date, Order Total, 
- Filter by:
  - Order status (e.g. completed, processing)
  - Date range
- CSV format download
- Built using OOP principles with namespaces and reusable classes

### Installation

1. Upload the plugin files to the `/wp-content/plugins/wooCommerce-orders-data-exporter-by-jmb` directory, or install the plugin through the WordPress Plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Navigate to **WooCommerce → Export Orders Data** to use the export tool.

### Screenshots

1. Admin page with export filters and field checkboxes
2. Exported CSV with email, name, and other selected fields

### Frequently Asked Questions

1. **Can I filter orders by product?**  
Yes, there is a product ID filter field in the export form.

2. **Can I choose which fields to export?**  
Yes, checkboxes allow you to include/exclude fields like name and phone.

3. **Does this export guest orders too?**  
Yes, guest customer emails and data are included as long as the order has a billing email.

### Changelog

**0.1.0**  
*Initial release with filtering by order date, status, product.*
*Selectable fields: Email, Name, Phone, Status, Order Date.*

**0.1.1** 
*Include the product SKU*
*Line item product cost*
*Bill to and Ship To information* 
*Shipping cost (if customer paid shipping or if it was free)*

**0.1.2** 
*Plugin rename*

### Upgrade Notice

**0.1.0**
Initial release.

**0.1.1** 
*Include the product SKU*
*Line item product cost*
*Bill to and Ship To information* 
*Shipping cost (if customer paid shipping or if it was free)*

**0.1.2** 
*Plugin rename*

### License

This plugin is licensed under the GPLv2 or later.

### Contact
If you are facing with any issues in this plugin, send a message to the following email address.
gswebsitedeveloper@gmail.com

### Customization Example
```php
<?php
add_action('init', function () {

    if ( is_plugin_active('woocommerce-data-export-by-jmb-main/woocommerce-data-export-by-jmb.php') && is_plugin_active('woocommerce-shipment-tracking/woocommerce-shipment-tracking.php') ) {

        $GLOBALS['jmb_export_tracking_enabled'] = false;

        add_action('jmb_export_fields', function() {

            $saved = get_option( JMB_ORDER_EXPORT_SETTINGS_OPTION, [] );
            $checked = !empty( $saved['export_tracking'] );
        
            ?>
            <label>
                <input type="checkbox"
                       name="export_tracking"
                       value="1"
                       <?php checked( $checked ); ?>>
                Order Tracking
            </label>
            <?php
        });


        add_filter('jmb_export_order_data_filter_args', function($args) {
            $args['export_tracking'] = !empty($_POST['export_tracking']) ? 1 : 0;
            $GLOBALS['jmb_export_tracking_enabled'] = ( $args['export_tracking'] == 1 );
            return $args;
        });

        add_filter('jmb_export_order_data_header', function($headers) {
            global $jmb_export_tracking_enabled;
            if (!empty($jmb_export_tracking_enabled)) {
                $headers[] = 'tracking_code';
            }
            return $headers;
        });

        add_filter('jmb_export_data_array', function($rows) {
            if (empty($GLOBALS['jmb_export_tracking_enabled'])) {
                return $rows;
            }

            foreach ($rows as $index => $row) {
                $order_id = $row[0]; // make sure order_id is the first column
                $order = wc_get_order($order_id);

                if (!$order) {
                    $rows[$index][] = '';
                    continue;
                }

                $tracking_items = (array) $order->get_meta('_wc_shipment_tracking_items');

                if (!empty($tracking_items)) {
                    $formatted = [];
                    foreach ($tracking_items as $item) {
                        $formatted[] = ($item['tracking_provider'] ?? '') . '-' . ($item['tracking_number'] ?? '');
                    }
                    $rows[$index][] = implode(', ', $formatted);
                } else {
                    $rows[$index][] = '';
                }
            }

            return $rows;
        });
        
        add_filter('jmb_order_export_save_settings', function($settings, $post){
            $settings['export_tracking'] = !empty($post['export_tracking']) ? 1 : 0;
            return $settings;
        }, 10, 2);

    }

});
?>

```