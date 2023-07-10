<?php

/*if (!SEMILON_ORDER_FILTERS_IS_ACTIVE)
    return;*/


if (!class_exists('Semilon_Order_Filters_Countries')) {
    class Semilon_Order_Filters_Countries extends Semilon_Order_Filters_Main
    {
        protected $name = 'country';
        protected $colection = 'countries';
        protected $item_tags = array('billing_country' => '_billing_country');

        public function __construct($isActive)
        {
            $this->field = array(
                'name'	  => __('Countries', SEMILON_ORDER_FILTERS_TRANSLATE_ID),
                'desc'    => __('Filter countries buy your products.', SEMILON_ORDER_FILTERS_TRANSLATE_ID),
                'id'	  => SEMILON_ORDER_FILTERS_ID . '_countries',
                'type'	  => 'checkbox',
                'default' => 'yse'
            );

            parent::__construct($isActive);
        }

        private function get_countries(){
            $countries_obj = new WC_Countries();
            $countries_array = $countries_obj->get_countries();

            return  $countries_array ;
        }
    }
}


