<?php

/*if (!SEMILON_ORDER_FILTERS_IS_ACTIVE)
    return;*/


if (!class_exists('Semilon_Order_Filters_Main')) {
    class Semilon_Order_Filters_Main
    {
        public $field = array(
            'name'	  => '',
            'desc'    => '',
            'id'	  => SEMILON_ORDER_FILTERS_ID . '_',
            'type'	  => 'checkbox',
            'default' => 'yse'
        );

        public function __construct($isActive)
        {
            $name = str_replace('_', ' ', $this->collection);
            $this->field['name'] = __(ucwords($name), SEMILON_ORDER_FILTERS_TRANSLATE_ID);
            $this->field['desc'] = __('Filter ' . $name . ' buy your products.', SEMILON_ORDER_FILTERS_TRANSLATE_ID);
            $this->field['id']  .=  $this->collection;

            $this->tag_name = SEMILON_ORDER_FILTERS_ID . '_' . $this->name;
            $this->load_filter($isActive);
        }

        public function load_filter($isActive) {
            if ( $isActive && is_admin() && ! defined( 'DOING_AJAX' ) ) {
                // adds the country filtering dropdown to the orders page
                add_action( 'restrict_manage_posts', array( $this, 'filter_by_item' ) );

                /*join filter*/
                add_filter( 'posts_join',  array( $this, 'add_item_join' ) );
                /*where query filter*/
                add_filter( 'posts_where', array( $this, 'add_item_where' ) );
            }
        }

        // ---------------------------------------  restrict_manage_posts
        public function filter_by_item()
        {
            $items = $this->get_list();
            echo $this->get_select_tag($items);
        }

        protected function get_list()
        {
            $item_tags = $this->generate_item_tags();
            global $wpdb;

            $joins = '';
            $wheres= '';
            $select= [];
            foreach ($item_tags as $item_tag) {
                $joins .= "	LEFT JOIN  {$wpdb->prefix}postmeta as {$item_tag[0]} ON {$item_tag[0]}.post_id=posts.ID ";
                $wheres.= " AND {$item_tag[0]}.meta_key ='{$item_tag[1]}' ";
                $select[] = " {$item_tag[0]}.meta_value as '{$item_tag[0]}' ";
            }
            $select = implode(', ', $select);


            $query = "
				SELECT 
				{$select}
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

            $rows = $this->validate_fetch_items($rows);

            return $rows;
        }
        private function generate_item_tags() {
            $keys = array_keys($this->item_tags);
            if(gettype($keys[0]) === 'integer'){
                return $this->item_tags;
            }

            $tags = [];
            foreach($this->item_tags as $key=>$value){
                $tags[] = [$key, $value];
            }
            $this->item_tags = $tags;
            return $tags;
        }
        protected function validate_fetch_items($fetch_items) {
            return $fetch_items;
        }

        private function get_select_tag($items)
        {
            $first_choice = __( 'Filter by order ' . $this->name, SEMILON_ORDER_FILTERS_TRANSLATE_ID );
            $class= SEMILON_ORDER_FILTERS_ID . '_controller';

            $options = $this->get_option_tags($items);

            return "<select name='{$this->tag_name}' id='{$this->tag_name}' class='{$class}'>
                        <option value=''>{$first_choice}</option>
                        {$options}
                    </select>";

        }
        protected function get_option_tags($items) {
            $option_value = $this->item_tags[0][0];
            $option_caption = isset($this->item_tags[1]) ? $this->item_tags[1][0] : $this->item_tags[0][0] . '_title';

            $options = '';
            foreach($items as $item){
                $value = esc_attr($item->$option_value);
                $selected = esc_attr( isset( $_GET[$this->tag_name] ) ? selected( $item->$option_value, $_GET[$this->tag_name], false ) : '' );
                $caption = esc_html( isset($item->$option_caption) ? $item->$option_caption : $item->$option_value );
                $options .= "<option value='{$value}' {$selected}>{$caption}</option>";
            }

            return $options;
        }
        // --------------------------------------- /restrict_manage_posts

        /**
         * Modify SQL JOIN for filtering the orders by any country name
         *
         *
         * @param string $join JOIN part of the sql query
         * @return string $join modified JOIN part of sql query
         */
        public function add_item_join($join){
            global $typenow, $wpdb;

            if ( 'shop_order' === $typenow && isset( $_GET[$this->tag_name] ) && ! empty( $_GET[$this->tag_name] ) ) {
                $item_tags = $this->generate_item_tags();
                $join .= "	LEFT JOIN  {$wpdb->prefix}postmeta as {$item_tags[0][0]} ON {$item_tags[0][0]}.post_id={$wpdb->posts}.ID ";
            }

            return $join;
        }

        /**
         * Modify SQL Where for filtering the orders by any country name
         *
         *
         * @param string $where WHERE part of the sql query
         * @return string $where modified WHERE part of sql query
         */
        public function add_item_where($where){
            global $typenow, $wpdb;

            if ( 'shop_order' === $typenow && isset( $_GET[$this->tag_name] ) && ! empty( $_GET[$this->tag_name] ) ) {
                $item_tags = $this->generate_item_tags();
                // prepare WHERE query part
                $where .= $wpdb->prepare(" AND {$item_tags[0][0]}.meta_key='{$item_tags[0][1]}' AND {$item_tags[0][0]}.meta_value='%s'", wc_clean( $_GET[$this->tag_name] ) );
                //$where .= " AND {$item_tags[0][0]}.meta_key ='{$item_tags[0][1]}' AND {$item_tags[0][0]}.meta_value='{$_GET[$this->tag_name]}'  ";
            }

            return $where;
        }

        protected function get_query($query)
        {
            return $query;
        }
    }
}


