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
            'default' => 'yes'
        );
        protected $name = '';
        protected $collection = '';

        /**
         * @var array
         * array of key-value array
         * key list:
         *   name  - name of fetching data and the join     (REQUIRED)
         *   value - value of the for fetching data         (REQUIRED)
         *   select_field - name of the field for fetching data [default: meta_value]
         *   where_field - name of the field for fetching data [default: meta_key]
         *   side1table - name of the table for the join [default: postmeta]
         *   side1field - name of the table for the join [default: post_id]
         *   side2table - name of the table for the join [default: posts]
         *   side2field - name of the table for the join [default: ID]
         *   group_by   - items to group [default: {name}.{select_field} | use (, ) for list of items]
         *   order_by   - items to order [default: {name}.{select_field}]
         *
         * sample (short):
                array(
                      array(
                          'name'  => 'billing_country',
                          'value  => '_billing_country'
                      )
                  )
         *
         * sample (complete):
                array(
                      array(
                          'name'  => 'billing_country',
                          'value  => '_billing_country',
                          'select_field' => 'meta_value',
                          'where_field' => 'meta_key',
                          'side1table' => 'postmeta',
                          'side1field' => 'post_id',
                          'side2table' => 'posts',
                          'side2field' => 'ID',
                          'group_by' => 'billing_country.meta_value',
                          'order_by' => 'billing_country.meta_value'
                      )
                  )
         */
        protected $joins = array();

        protected $tag_type = 'select';

        public function __construct($isActive)
        {
            $this->fill_field();
            $this->tag_name = SEMILON_ORDER_FILTERS_ID . '_' . $this->name;
            $this->load_filter($isActive);
        }

        private function fill_field() {
            $this->fill_collection();
            $name = str_replace('_', ' ', $this->collection);
            $this->field['name'] = __(ucwords($name), SEMILON_ORDER_FILTERS_TRANSLATE_ID);
            $this->field['desc'] = __('Filter ' . $name . ' buy your products.', SEMILON_ORDER_FILTERS_TRANSLATE_ID);
            $this->field['id']  .=  $this->collection;
        }

        private function fill_collection()
        {
            $this->collection = strtolower(get_class($this));
            $this->collection = str_replace(SEMILON_ORDER_FILTERS_ID.'_', '', $this->collection);
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
            switch ( $this->tag_type ) {
                case 'select':
                    $items = $this->get_list();
                    echo $this->get_select_tag($items);
                    break;
                case 'text':
                default:
                    echo $this->get_text_tag();
                    break;
            }
        }

        protected function get_list()
        {
            $query = count($this->joins) ? $this->get_list_with_join_to_postmeta() : $this->get_list_from_post();

            $query = $this->get_query($query);

            global $wpdb;
            $rows = $wpdb->get_results($query);

            $rows = $this->validate_fetch_items($rows);

            return $rows;
        }

        protected function get_list_with_join_to_postmeta()
        {
            $item_tags = $this->generate_item_tags();
            global $wpdb;

            $joins = '';
            $wheres= '';
            $select= [];
            foreach ($item_tags as $item_tag) {
                $joins  .= "	LEFT JOIN  {$wpdb->prefix}{$item_tag['side1table']} as {$item_tag['name']} ON {$item_tag['name']}.{$item_tag['side1field']}={$item_tag['side2table']}.{$item_tag['side2field']} ";
                $wheres .= " AND {$item_tag['name']}.{$item_tag['where_field']} ='{$item_tag['value']}' ";
                $select[]= " {$item_tag['name']}.{$item_tag['select_field']} as '{$item_tag['name']}' ";
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
				GROUP BY {$item_tags[0]['group_by']}
				Order BY {$item_tags[0]['order_by']} ASC";

            return $query;
        }

        protected function get_list_from_post()
        {
            $name = 'post_' . $this->name;

            global $wpdb;
            $query = "
				SELECT 
				posts.{$name} as {$this->name}
				FROM {$wpdb->prefix}posts as posts
				WHERE 1=1
				AND posts.post_type ='shop_order'
				GROUP BY {$this->name}
				Order BY {$this->name} ASC";

            return $query;
        }

        private function generate_item_tags() {
            foreach($this->joins as $key=> $value){
                if(!isset($value['select_field'])) {
                    $this->joins[$key]['select_field'] = 'meta_value';
                }

                if(!isset($value['where_field'])) {
                    $this->joins[$key]['where_field'] = 'meta_key';
                }

                if(!isset($value['side1table'])) {
                    $this->joins[$key]['side1table'] = 'postmeta';
                }

                if(!isset($value['side1field'])) {
                    $this->joins[$key]['side1field'] = 'post_id';
                }

                if(!isset($value['side2table'])) {
                    $this->joins[$key]['side2table'] = 'posts';
                }

                if(!isset($value['side2field'])) {
                    $this->joins[$key]['side2field'] = 'ID';
                }

                if(!isset($value['group_by'])) {
                    $this->joins[$key]['group_by'] = $this->joins[$key]['name'] . '.' . $this->joins[$key]['select_field'];
                }

                if(!isset($value['order_by'])) {
                    $this->joins[$key]['order_by'] = $this->joins[$key]['name'] . '.' . $this->joins[$key]['select_field'];
                }
            }
            return $this->joins;
        }
        protected function validate_fetch_items($fetch_items) {
            return $fetch_items;
        }

        // --------------------------------  select tag
        private function get_select_tag($items)
        {
            $first_choice = __( 'Filter by order ' . str_replace('_', ' ', $this->name), SEMILON_ORDER_FILTERS_TRANSLATE_ID );
            $class= SEMILON_ORDER_FILTERS_ID . '_controller';

            $options = $this->get_option_tags($items);

            return "<select name='{$this->tag_name}' id='{$this->tag_name}' class='{$class}'>
                        <option value=''>{$first_choice}</option>
                        {$options}
                    </select>";

        }
        private function get_option_tags($items) {
            if(count($this->joins)) {
                $option_value = $this->joins[0]['name'];
                $option_caption = isset($this->joins[1]) ? $this->joins[1]['name'] : $this->joins[0]['name'] . '_title';
            } else {
                $option_value = $this->name;
                $option_caption = $this->name;
            }

            $options = '';
            foreach($items as $item){
                $value = esc_attr($item->$option_value);
                $selected = esc_attr( isset( $_GET[$this->tag_name] ) ? selected( $item->$option_value, $_GET[$this->tag_name], false ) : '' );
                $caption = esc_html( isset($item->$option_caption) ? $item->$option_caption : $item->$option_value );
                $caption = str_replace('wc-', '', $caption);
                $options .= "<option value='{$value}' {$selected}>{$caption}</option>";
            }

            return $options;
        }
        // --------------------------------  text tag
        private function get_text_tag()
        {
            $label = str_replace('_', ' ', $this->name);
            $placeholder = __( 'Filter by order ' . $label, SEMILON_ORDER_FILTERS_TRANSLATE_ID );
            $class = SEMILON_ORDER_FILTERS_ID . '_controller';
            $value = isset( $_GET[$this->tag_name] )  ? $_GET[$this->tag_name] : '';

            return "<input type='text' name='{$this->tag_name}' id='{$this->tag_name}' class='{$class}' placeholder='{$placeholder}' value='{$value}' title='{$label}' />";

        }
        // --------------------------------------- /restrict_manage_posts

        /**
         * Modify SQL JOIN for filtering the orders by any country name
         *
         *
         * @param string $join JOIN part of the sql query
         * @return string $join modified JOIN part of sql query
         */
        public function add_item_join($join) {
            if(count($this->joins)) {
                global $typenow, $wpdb;

                if ('shop_order' === $typenow && isset($_GET[$this->tag_name]) && !empty($_GET[$this->tag_name])) {
                    $item_tags = $this->generate_item_tags();
                    $join .= "	LEFT JOIN  {$wpdb->prefix}{$item_tags[0]['side1table']} as {$item_tags[0]['name']} ON {$item_tags[0]['name']}.{$item_tags[0]['side1field']}={$wpdb->posts}.ID ";
                }
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
        public function add_item_where($where) {
            global $typenow, $wpdb;

            if ( 'shop_order' === $typenow && isset( $_GET[$this->tag_name] ) && ! empty( $_GET[$this->tag_name] ) ) {
                $item_tags = $this->generate_item_tags();

                // prepare WHERE query part
                switch ($this->tag_type) {
                    case 'select':
                        if(count($this->joins)) {
                            $where .= $wpdb->prepare(" AND {$item_tags[0]['name']}.{$item_tags[0]['where_field']}='{$item_tags[0]['value']}' AND {$item_tags[0]['name']}.{$item_tags[0]['select_field']}='%s'", wc_clean($_GET[$this->tag_name]));
                        } else {
                            $name = 'post_' . $this->name;
                            $where .= $wpdb->prepare(" AND {$wpdb->posts}.{$name}='%s'", wc_clean($_GET[$this->tag_name]));
                        }
                        break;
                    case 'text':
                    default:
                        $where .= " AND {$item_tags[0]['name']}.{$item_tags[0]['where_field']} ='{$item_tags[0]['value']}' AND {$item_tags[0]['name']}.{$item_tags[0]['select_field']} LIKE '%{$_GET[$this->tag_name]}%'  ";
                        break;
                }
            }

            return $where;
        }

        protected function get_query($query)
        {
            return $query;
        }
    }
}


