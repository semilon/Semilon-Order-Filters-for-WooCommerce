<?php

/*if (!SEMILON_ORDER_FILTERS_IS_ACTIVE)
    return;*/


if (!class_exists('Semilon_Order_Filters_Setting')) {
    class Semilon_Order_Filters_Setting
    {
        public function __construct()
        {
            $this->id = SEMILON_ORDER_FILTERS_ID;

            $this->current_tab = ( isset( $_GET[ 'tab' ] ) ) ? $_GET[ 'tab' ] : 'general';

            // Tab under WooCommerce settings
            $this->settings_tabs = array(
                $this->id => 'Order Filters'
            );

            add_filter( 'plugin_action_links_' . $this->id, array( $this, 'action_links' ) );

            add_action( 'woocommerce_settings_tabs', array( $this, 'add_tab' ), 10 );

            foreach ( $this->settings_tabs as $name => $label ) {
                add_action( 'woocommerce_settings_tabs_' . $name, array( $this, 'settings_tab_action' ), 10 );
            }
        }

        /**
         * Add action links under WordPress > Plugins
         *
         * @param $links
         * @return array
         */
        public function action_links( $links ) {

            $settings_slug = 'woocommerce';

            if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0 ) {

                $settings_slug = 'wc-settings';
            }

            $plugin_links = array(
                '<a href="' . admin_url( 'admin.php?page=' . $settings_slug . '&tab=' . $this->id ) . '">' . __( 'Settings', 'woocommerce' ) . '</a>',
            );

            return array_merge( $plugin_links, $links );
        }

        /**
         * @access public
         * @return void
         */
        public function add_tab() {

            $settings_slug = 'woocommerce';

            if ( version_compare( WOOCOMMERCE_VERSION, "2.1.0" ) >= 0 ) {

                $settings_slug = 'wc-settings';
            }

            foreach ( $this->settings_tabs as $name => $label ) {
                $class	 = 'nav-tab';
                if ( $this->current_tab == $name )
                    $class	 .= ' nav-tab-active';
                echo '<a href="' . admin_url( 'admin.php?page=' . $settings_slug . '&tab=' . $name ) . '" class="' . $class . '">' . $label . '</a>';
            }
        }

        /**
         * @access public
         * @return void
         */
        public function settings_tab_action() {

            global $woocommerce_settings;

            // Determine the current tab in effect.
            $current_tab = $this->get_tab_in_view( current_filter(), 'woocommerce_settings_tabs_' );

            // Load the prepared form fields.
            $this->init_form_fields();

            if ( is_array( $this->fields ) )
                foreach ( $this->fields as $k => $v )
                    $woocommerce_settings[ $k ] = $v;

            // Display settings for this tab (make sure to add the settings to the tab).
            woocommerce_admin_fields( $woocommerce_settings[ $current_tab ] );
        }

        /**
         * Get the tab current in view/processing.
         */
        public function get_tab_in_view( $current_filter, $filter_base ) {

            return str_replace( $filter_base, '', $current_filter );
        }

        /**
         * Prepare form fields to be used in the various tabs.
         */
        public function init_form_fields() {

            // Define settings
            $this->fields[ $this->id ] = array(
                array(
                    'name' => __('Semilon Order Filters for WooCommerce', SEMILON_ORDER_FILTERS_TRANSLATE_ID),
                    'type' => 'title',
                    'desc' => __('You can manage filters. These are applied to the WooCommerce Order List. Every items have tick the filters that you want to apply.', SEMILON_ORDER_FILTERS_TRANSLATE_ID),
                    'id' => $this->id . '_options' ),
                array(
                    'name'		 => __('Countries', SEMILON_ORDER_FILTERS_TRANSLATE_ID),
                    'desc' => __('Filter countries buy your products.', SEMILON_ORDER_FILTERS_TRANSLATE_ID),
                    'id'		 => $this->id . '_countries',
                    'type'		 => 'checkbox',
                    'default'	 => 'yse'
                ),
                array(
                    'name'		 => __('Payment Method', SEMILON_ORDER_FILTERS_TRANSLATE_ID),
                    'desc' => __('Filter payment methods use in orders.', SEMILON_ORDER_FILTERS_TRANSLATE_ID),
                    'id'		 => $this->id . '_payment_method',
                    'type'		 => 'checkbox',
                    'default'	 => 'yse'
                ),
                array( 'type' => 'sectionend', 'id' => $this->id . '_options' ),
            ); // End settings
        }

        /**
         * Add settings fields for each tab.
         */
        public function add_settings_fields() {

            global $woocommerce_settings;

            // Load the prepared form fields.
            $this->init_form_fields();

            if ( is_array( $this->fields ) )
                foreach ( $this->fields as $k => $v )
                    $woocommerce_settings[ $k ] = $v;
        }
    }
}


