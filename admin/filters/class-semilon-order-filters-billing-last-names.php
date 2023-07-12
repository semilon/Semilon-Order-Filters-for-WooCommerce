<?php

/*if (!SEMILON_ORDER_FILTERS_IS_ACTIVE)
    return;*/


if (!class_exists('Semilon_Order_Filters_Billing_Last_Names')) {
    class Semilon_Order_Filters_Billing_Last_Names extends Semilon_Order_Filters_Main
    {
        protected $name = 'billing_last_name';
        protected $tag_type = 'text';
        protected $joins = array(array(
            'name' => 'billing_last_name',
            'value'=> '_billing_last_name'
        ));
    }
}