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
    }
}


