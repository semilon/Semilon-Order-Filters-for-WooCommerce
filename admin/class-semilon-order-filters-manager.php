<?php

/*if (!SEMILON_ORDER_FILTERS_IS_ACTIVE)
    return;*/


if (!class_exists('Semilon_Order_Filters_Manager')) {
    class Semilon_Order_Filters_Manager
    {
        private $base = __DIR__ . DIRECTORY_SEPARATOR . 'filters';

        public function __construct()
        {
            $this->list();
        }

        private function list($exceptions = array())
        {
            $files = scandir($this->base);
            unset($files[0]);
            unset($files[1]);

            $list = array();

            GLOBAL $Semilon_order_filters_fields;

            foreach ($files as $index => $file) {
                if (is_file($this->base . DIRECTORY_SEPARATOR . $file) && !in_array($file, $exceptions)) {
                    $Semilon_order_filters_fields[] = $this->load_filter($file);
                }
            }
        }

        private function fetch_filter_name($file)
        {
            $filter = preg_replace('/class-semilon-order-filters-([a-z\-]+).php/i', '${1}', $file);
            return implode('_', explode('-', $filter));
        }

        private function fetch_class_name($file)
        {
            $class = preg_replace('/class-semilon-order-filters-([a-z\-]+).php/i', '${1}', $file);
            $class = implode(' ', explode('-', $class));
            $class = ucwords($class);
            $class = implode('_', explode(' ', $class));
            return 'Semilon_Order_Filters_' . $class;
        }

        private function load_filter($file) {
            $filter = $this->fetch_filter_name($file);
            $object = $this->fetch_class_name($file);

            $active = get_option(SEMILON_ORDER_FILTERS_ID . '_' . $filter) === 'yes';
            require_once($this->base . DIRECTORY_SEPARATOR . $file);
            return (new $object($active))->field;
        }

        private function get_filter_status($filter)
        {
            $option_name = SEMILON_ORDER_FILTERS_ID . '_' . $filter;
            $status = get_option($option_name, '#');
            if($status === '#') {
                update_option($option_name, 'yes');
                $status = 'yes';
            }

            return $status;
        }
    }
}


