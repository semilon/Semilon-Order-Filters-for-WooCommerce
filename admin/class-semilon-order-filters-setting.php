<?php

/*if (!SEMILON_ORDER_FILTERS_IS_ACTIVE)
    return;*/


if (!class_exists('Semilon_Order_Filters_Setting')) {
    class Semilon_Order_Filters_Setting
    {
        public function __construct()
        {
            $this->id = SEMILON_ORDER_FILTERS_ID;

            $this->current_tab = ( isset( $_GET[ 'tab' ] ) ) ? $_GET[ 'tab' ] : 'general';

            // Tab under WooCommerce settings
            $this->settings_tabs = array(
                $this->id => 'Order Filters'
            );
        }
    }
}


