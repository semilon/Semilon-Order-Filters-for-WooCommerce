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

    define( 'SEMILON_ORDER_FILTERS_IS_ACTIVE', in_array('semilon-order-filters-for-woocommerce/main.php', apply_filters('active_plugins', get_option('active_plugins'))) );
    define( 'SEMILON_ORDER_FILTERS_ID', 'semilon_order_filters' );
    define( 'SEMILON_ORDER_FILTERS_TRANSLATE_ID', 'semilon-order-filters' );
    define( 'SEMILON_ORDER_FILTERS_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );
    define( 'SEMILON_ORDER_FILTERS_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

    $Semilon_order_filters_fields = array();

    class Semilon_Order_Filters_For_Woocommerce
    {
        public function __construct()
        {
            add_action('admin_notices', array($this, 'semilon_check_woocommece_active'));
            $this->setting();
            $this->support();
        }

        public function semilon_check_woocommece_active()
        {
            if (!is_plugin_active('woocommerce/woocommerce.php')) {
                esc_html_e("<div class='error'><p><strong>Semilon Country Sales Report For WooCommerce</strong> requires <strong> WooCommerce active plugin</strong> </p></div>");
            }
        }

        private function setting()
        {
            require_once __DIR__ . '/admin/class-semilon-order-filters-setting.php';
            new Semilon_Order_Filters_Setting();
        }

        private function support()
        {
            require_once __DIR__ . '/admin/class-semilon-order-filters-support.php';
            new Semilon_Order_Filters_Support();
        }
    }

    new Semilon_Order_Filters_For_Woocommerce();
}
