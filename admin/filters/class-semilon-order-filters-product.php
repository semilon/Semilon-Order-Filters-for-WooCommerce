<?php

/*if (!SEMILON_ORDER_FILTERS_IS_ACTIVE)
    return;*/


if (!class_exists('Semilon_Order_Filters_Product')) {
    class Semilon_Order_Filters_Product extends Semilon_Order_Filters_Main
    {
        protected $name = 'product';
        protected $joins = array(
            array(
                'name'  => 'order_items',
                'value'  => 'line_item',
                'select_field' => 'order_item_name',
                'where_field' => 'order_item_type',
                'side1table' => 'woocommerce_order_items',
                'side1field' => 'order_id',
                'group_by' => 'product_id.meta_value, variation_id.meta_value',
            ),
            array(
                'name'  => 'product_id',
                'value'  => '_product_id',
                'side1table' => 'woocommerce_order_itemmeta',
                'side1field' => 'order_item_id',
                'side2table' => 'order_items',
                'side2field' => 'order_item_id',
            ),
            array(
                'name'  => 'variation_id',
                'value'  => '_variation_id',
                'side1table' => 'woocommerce_order_itemmeta',
                'side1field' => 'order_item_id',
                'side2table' => 'order_items',
                'side2field' => 'order_item_id',
            )
        );
    }
}