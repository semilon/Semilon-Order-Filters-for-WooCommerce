<?php

/*if (!SEMILON_ORDER_FILTERS_IS_ACTIVE)
    return;*/


if (!class_exists('Semilon_Order_Filters_Countries')) {
    class Semilon_Order_Filters_Countries extends Semilon_Order_Filters_Main
    {
        protected $name = 'country';
        protected $item_tags = array('billing_country' => '_billing_country');

        protected function validate_fetch_items($fetch_items) {
            $countries = $this->get_countries();

            foreach($fetch_items as $key=>$value){
                if(isset($countries[$value->billing_country])) {
                    $fetch_items[$key]->billing_country_title = $countries[$value->billing_country];
                }
            }

            return $fetch_items;
        }
        private function get_countries(){
            $countries_obj = new WC_Countries();
            $countries_array = $countries_obj->get_countries();

            return  $countries_array ;
        }
    }
}


