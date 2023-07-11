<?php

/*if (!SEMILON_ORDER_FILTERS_IS_ACTIVE)
    return;*/


if (!class_exists('Semilon_Order_Filters_Payment_Method')) {
    class Semilon_Order_Filters_Payment_Method extends Semilon_Order_Filters_Main
    {
        protected $name = 'payment_method';
        protected $colection = 'payment_methods';
        protected $item_tags = array(
            'payment_method'       => '_payment_method',
            'payment_method_title' => '_payment_method_title'
        );
    }
}