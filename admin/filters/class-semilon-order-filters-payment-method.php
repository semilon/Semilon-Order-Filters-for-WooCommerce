<?php

/*if (!SEMILON_ORDER_FILTERS_IS_ACTIVE)
    return;*/


if (!class_exists('Semilon_Order_Filters_Payment_Method')) {
    class Semilon_Order_Filters_Payment_Method extends Semilon_Order_Filters_Main
    {
        protected $name = 'payment_method';
    }
}