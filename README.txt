=== Codenitive WooCommerce Orders Data Exporter ===
Contributors: Gurjit Singh  
Tags: woocommerce, email export, order exporter, csv export  
Requires at least: 5.6  
Tested up to: 6.5  
Requires PHP: 7.4  
Stable tag: 1.0.0  
License: GPLv2 or later  
License URI: https://www.gnu.org/licenses/gpl-2.0.html  

Export WooCommerce orders data details to CSV with filtering options. Supports filtering by date and order status.

== Description ==

**Codenitive WooCommerce Orders Data Exporter** is a simple plugin to export WooCommerce orders data, like billing name, phone, order status, and order date.  
It also supports exporting additional fields like billing name, phone, order status, and order date.

**Key Features:**
- Export WooCommerce orders data
- Optional fields: Name, Phone, Order Status, Order Date
- Filter by:
  - Order status (e.g. completed, processing)
  - Date range
- CSV format download
- Built using OOP principles with namespaces and reusable classes

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/wooCommerce-orders-data-exporter-by-jmb` directory, or install the plugin through the WordPress Plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Navigate to **WooCommerce → Export Orders Data** to use the export tool.

== Screenshots ==

1. Admin page with export filters and field checkboxes
2. Exported CSV with email, name, and other selected fields

== Frequently Asked Questions ==

= Can I filter orders by product? =
Yes, there is a product ID filter field in the export form.

= Can I choose which fields to export? =
Yes, checkboxes allow you to include/exclude fields like name and phone.

= Does this export guest orders too? =
Yes, guest customer emails and data are included as long as the order has a billing email.

== Changelog ==

= 0.1.2 =
Plugin rename

= 0.1.1 =
* Include the product SKU
* Line item product cost
* Bill to and Ship To information
* Shipping cost (if customer paid shipping or if it was free)

= 0.1.0 =
* Initial release with filtering by order date, status, product.
* Selectable fields: Email, Name, Phone, Status, Order Date.

== Upgrade Notice ==

= 0.1.2 =
Plugin rename

= 0.1.1 =
* Include the product SKU
* Line item product cost
* Bill to and Ship To information
* Shipping cost (if customer paid shipping or if it was free)

= 0.1.0 =
Initial release.

== License ==

This plugin is licensed under the GPLv2 or later.
