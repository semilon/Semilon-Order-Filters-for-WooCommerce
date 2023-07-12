<?php

/*if (!SEMILON_ORDER_FILTERS_IS_ACTIVE)
    return;*/


if (!class_exists('Semilon_Order_Filters_Billing_First_Names')) {
    class Semilon_Order_Filters_Billing_First_Names extends Semilon_Order_Filters_Main
    {
        protected $name = 'billing_first_name';
        protected $tag_type = 'text';
        protected $joins = array(
            'billing_first_name'       => '_billing_first_name'
        );
    }
}