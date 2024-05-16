<?php
/**
 * Plugin Name: MStore Hooks
 * Description: Just some hooks for Mstore plugins
 * Plugin URI: https://sz4h.com/
 * Author: Ahmed Safaa
 * Version: 1.0.0
 * Author URI: https://sz4h.com/
 *
 * Text Domain: space-mstore-hooks
 *
 */

if (!defined('ABSPATH')) {
    exit;
}

/* Accept Customer IP Address in Mstore API Route */
add_action('woocommerce_rest_insert_shop_order_object', 'my_woocommerce_rest_insert_shop_order_object', 10, 2);
function my_woocommerce_rest_insert_shop_order_object(WC_Order $order, $request): void
{
    if (isset($request['customer_ip_address']) && $request['customer_ip_address'] != '') {
        $order->update_meta_data('customer_ip_address', $request['customer_ip_address']);
    }
}

/* Show Customer IP in Woocommerce Order Admin Page */
add_action('woocommerce_admin_order_data_after_order_details', 'my_woocommerce_admin_order_data_after_order_details', 44, 1);
function my_woocommerce_admin_order_data_after_order_details($order): void
{
    if (is_numeric($order)) {
        $order = wc_get_order($order);
    }
    $order_id = $order instanceof WC_Order ? $order->get_id() : null;
    if (!is_numeric($order_id) || $order_id == 0) {
        return;
    }
    ?>
    <p class='form-field form-field-wide wc-customer-ip-address'>
        <label for='customer-ip-address'><?php esc_html_e('Customer IP Address:', 'woocommerce'); ?></label>
        <strong><?php echo esc_html($order->get_meta('customer_ip_address')); ?></strong>
    </p>
    <?php

}