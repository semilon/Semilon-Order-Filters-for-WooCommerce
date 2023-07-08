<?php

/*if (!SEMILON_ORDER_FILTERS_IS_ACTIVE)
    return;*/


if (!class_exists('Semilon_Order_Filters_Manager')) {
    class Semilon_Order_Filters_Manager
    {
        public function __construct()
        {
        }

        private function fetch_class_name($file)
        {
            $class = preg_replace('/class-semilon-order-filters-([a-z\-]+).php/i', '${1}', $file);
            $class = implode(' ', explode('-', $class));
            $class = ucwords($class);
            $class = implode('_', explode(' ', $class));
            return 'Semilon_Order_Filters_' . $class;
        }
    }
}


