<?php

/*if (!SEMILON_ORDER_FILTERS_IS_ACTIVE)
    return;*/


if (!class_exists('Semilon_Order_Filters_Status')) {
    class Semilon_Order_Filters_Status extends Semilon_Order_Filters_Main
    {
        protected $name = 'status';
    }
}