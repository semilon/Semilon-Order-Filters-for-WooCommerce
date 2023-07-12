<?php

/*if (!SEMILON_ORDER_FILTERS_IS_ACTIVE)
    return;*/


if (!class_exists('Semilon_Order_Filters_Payment_Methods')) {
    class Semilon_Order_Filters_Payment_Methods extends Semilon_Order_Filters_Main
    {
        protected $name = 'payment_method';
        protected $joins = array(
            array(
                'name' => 'payment_method',
                'value'=> '_payment_method'
            ),
            array(
                'name' => 'payment_method_title',
                'value'=> '_payment_method_title'
            )
        );
    }
}