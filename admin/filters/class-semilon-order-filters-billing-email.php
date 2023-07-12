<?php

/*if (!SEMILON_ORDER_FILTERS_IS_ACTIVE)
    return;*/


if (!class_exists('Semilon_Order_Filters_Billing_Email')) {
    class Semilon_Order_Filters_Billing_Email extends Semilon_Order_Filters_Main
    {
        protected $name = 'user_email';
        protected $joins = array(array(
            'name' => 'billing_email',
            'value'=> '_billing_email'
        ));
    }
}