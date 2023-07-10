<?php

/*if (!SEMILON_ORDER_FILTERS_IS_ACTIVE)
    return;*/


if (!class_exists('Semilon_Order_Filters_Main')) {
    class Semilon_Order_Filters_Main
    {
        public $field = array();

        public function __construct($isActive)
        {
        }

        public function filter_by_item()
        {
            $items = $this->get_list();
        }

        protected function get_list()
        {
            $item_tags = $this->generate_item_tags();
            global $wpdb;

            $joins = '';
            $wheres= '';
            foreach ($item_tags as $item_tag) {
                $joins .= "	LEFT JOIN  {$wpdb->prefix}postmeta as {$item_tag[0]} ON {$item_tag[0]}.post_id=posts.ID ";
                $wheres.= " AND {$item_tag[0]}.meta_key ='{$item_tag[1]}' ";
            }


            $query = "
				SELECT 
				{$item_tags[0][0]}.meta_value as '{$item_tags[0][0]}'
				FROM {$wpdb->prefix}posts as posts
				{$joins}
				WHERE 1=1
				AND posts.post_type ='shop_order'
				{$wheres}
				GROUP BY {$item_tags[0][0]}.meta_value
				Order BY {$item_tags[0][0]}.meta_value ASC";

            //$query = $wpdb->prepare($query );
            //$rows = $wpdb->get_results( $wpdb->prepare($query ));
            $rows = $wpdb->get_results($query );

            return $rows;
        }
        private function generate_item_tags() {
            $tags = [];
            foreach($this->item_tags as $key=>$value){
                $tags[] = [$key, $value];
            }
            $this->item_tags = $tags;
            return $tags;
        }
        public function validate_fetch_items($fetch_items) {
            return $fetch_items;
        }
    }
}


