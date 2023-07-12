<?php

/*if (!SEMILON_ORDER_FILTERS_IS_ACTIVE)
    return;*/


if (!class_exists('Semilon_Order_Filters_Coupon')) {
    class Semilon_Order_Filters_Coupon extends Semilon_Order_Filters_Main
    {
        protected $name = 'coupon';
        protected $joins = array(
            array(
                'name'  => 'order_items',
                'value'  => 'coupon',
                'select_field' => 'order_item_name',
                'where_field' => 'order_item_type',
                'side1table' => 'woocommerce_order_items',
                'side1field' => 'order_id',
            ),
        );
    }
}