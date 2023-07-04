<?php
/**
 * @link            https://Semilon.dev/
 * @since           1.0.0
 * @package         Pass_WooCommerce_Shipping
 *
 * @wordpress-plugin
 * Plugin Name:     Semilon Order Filters for WooCommerce
 * Plugin URI:      https://github.com/semilon/semilon-order-filters-for-woocommerce
 * Description:     all order filters for woocommerce
 * Version:         1.0.0
 * Author:          Semilon
 * Author:          Mostafa Sharami
 * Author:          Majid Vahidkhoo
 * Author URI:      https://Semilon.dev/
 * Author URI:      http://MostafaSharami.com/
 * Author URI:      http://Majva.com/
 *
 * Copyright:       © 2023 https://Semilon.dev/.
 * License:         MIT License
 */

if(!defined('ABSPATH'))
    exit;

if(!class_exists('Semilon_Order_Filters_For_Woocommerce')) {
    class Semilon_Order_Filters_For_Woocommerce
    {
        public function __construct()
        {
            add_action('admin_notices', array($this, 'semilon_check_woocommece_active'));
        }

        public function semilon_check_woocommece_active()
        {
            if (!is_plugin_active('woocommerce/woocommerce.php')) {
                esc_html_e("<div class='error'><p><strong>Semilon Country Sales Report For WooCommerce</strong> requires <strong> WooCommerce active plugin</strong> </p></div>");
            }
        }
    }

    new Semilon_Order_Filters_For_Woocommerce();
}
