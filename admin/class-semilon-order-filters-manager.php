<?php

/*if (!SEMILON_ORDER_FILTERS_IS_ACTIVE)
    return;*/


if (!class_exists('Semilon_Order_Filters_Manager')) {
    class Semilon_Order_Filters_Manager
    {
        private $base = __DIR__ . DIRECTORY_SEPARATOR . 'filters';

        public function __construct()
        {
            if($this->check_page('post_type', 'shop_order') || $this->check_page('tab', 'semilon_order_filters')) {
                $this->list();
            }
        }

        private function list($exceptions = array())
        {
            GLOBAL $Semilon_order_filters_fields;

            foreach ($this->get_files_list() as $file) {
                if (is_file($this->base . DIRECTORY_SEPARATOR . $file) && !in_array($file, $exceptions)) {
                    $Semilon_order_filters_fields[] = $this->load_filter($file);
                }
            }
        }

        private function get_files_list()
        {
            $files = scandir($this->base);

            $last_file = count($files) - 1;
            if(str_contains($files[$last_file], '_')) {
                unset($files[$last_file]);
            }
            unset($files[0]);
            unset($files[1]);

            return $files;
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

            $active = $this->get_filter_status($filter) === 'yes';
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

        private function check_page($key, $value)
        {
            $get_val = sanitize_text_field( isset($_GET[$key]) ? $_GET[$key] : '');
            return $get_val == $value;
        }
    }
}


