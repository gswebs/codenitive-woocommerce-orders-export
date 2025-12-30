<?php
namespace WooJMBExporter;

use WC_Order;

defined('ABSPATH') || exit;

class OrderDataExporter extends BaseExporter {

    protected $filters = [];
    
    public function __construct($filters = []) {
        $this->filters = $filters;
    }
    
    private function format_price_csv( $amount, WC_Order $order ) {
        $currency_code = get_woocommerce_currency();
        $currency = html_entity_decode(get_woocommerce_currency_symbol($currency_code));
        $price = $currency . number_format((float) $amount, 2);
    
        // Remove any spaces
        $price = str_replace(' ', '', $price);
    
        return $price;
    }

    protected function fetch_data(): void {
        $args = [
            'limit'  => -1,
            'return' => 'ids',
        ];
    
        // Ensure status filter is passed as a string
        if (!empty($this->filters['statuses'])) {
            $args['status'] = implode(',', array_map('sanitize_text_field', $this->filters['statuses']));
        }
    
        // Add date range filters if provided
        if (!empty($this->filters['date_from']) && !empty($this->filters['date_to'])) {
            $date_from = $this->filters['date_from'];
            $date_to = $this->filters['date_to'];
            $args['date_created'] = $date_from.'...'.$date_to;
        }
        
        $args_ar = apply_filters('jmb_fetch_data_args', $args);
    
        // Ensure we only get real orders and not refunds
        $order_ids = wc_get_orders(array_merge($args_ar, [
            'type' => 'shop_order',  // Only actual orders (not refunds)
        ]));
    
        $export_data = [];
        
        foreach ($order_ids as $order_id) {
            $order = wc_get_order($order_id);
            if (!$order) continue;
        
            // Initialize arrays BEFORE looping items
            $products     = [];
            $product_skus = [];
            $line_costs   = [];
        
            foreach ($order->get_items() as $item) {
                $product = $item->get_product();
        
                $products[] = $item->get_name() . ' (x' . $item->get_quantity() . ')';
        
                if ($product) {
                    $product_skus[] = $product->get_sku();
                }
                
                $line_costs[] = $this->format_price_csv($item->get_total(), $order);
            }
        
            $billing_address = trim(
                $order->get_billing_address_1() . ' ' .
                $order->get_billing_address_2() . ', ' .
                $order->get_billing_city() . ', ' .
                $order->get_billing_state() . ', ' .
                $order->get_billing_postcode() . ', ' .
                $order->get_billing_country()
            );
        
            $shipping_address = trim(
                $order->get_shipping_address_1() . ' ' .
                $order->get_shipping_address_2() . ', ' .
                $order->get_shipping_city() . ', ' .
                $order->get_shipping_state() . ', ' .
                $order->get_shipping_postcode() . ', ' .
                $order->get_shipping_country()
            );
        
            $methods = [];

            foreach ($order->get_shipping_methods() as $shipping_item) {
                $methods[] = $shipping_item->get_method_title();
            }
            // Add shipping method(s) to row
            $shipping_method = !empty($methods)
                ? implode(', ', $methods)
                : '';
        
            $shipping_total = (float) $order->get_shipping_total();
            $shipping_cost  = $shipping_total > 0
                ? $this->format_price_csv($shipping_total, $order) // plain formatted number with currency
                : '0.00';
        
            $order_total = $this->format_price_csv($order->get_total(), $order);
        
            // Prepare CSV row
            $order_data = [];
        
            foreach ($this->filters['selected_fields'] as $field) {
                switch ($field) {
                    case 'order_id':       $order_data[] = $order->get_id(); break;
                    case 'email':          $order_data[] = $order->get_billing_email(); break;
                    case 'name':           $order_data[] = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name(); break;
                    case 'phone':          $order_data[] = $order->get_billing_phone(); break;
                    case 'billing_addr':   $order_data[] = $billing_address; break;
                    case 'shipping_addr':  $order_data[] = $shipping_address; break;
                    case 'products':       $order_data[] = implode(', ', $products); break;
                    case 'product_sku':    $order_data[] = implode(', ', array_filter($product_skus)); break;
                    case 'line_cost':      $order_data[] = implode(', ', $line_costs); break;
                    case 'shipping_method':  $order_data[] = $shipping_method; break;
                    case 'shipping_cost':  $order_data[] = $shipping_cost; break;
                    case 'status':         $order_data[] = $order->get_status(); break;
                    case 'order_total':    $order_data[] = $order_total; break;
                    case 'order_date':     $order_data[] = $order->get_date_created()->date('Y-m-d H:i:s'); break;
                }
            }
        
            $export_data[] = $order_data;
        }
    
        $this->data = apply_filters('jmb_export_data_array', $export_data);
    }

}
