<?php

/*if (!SEMILON_ORDER_FILTERS_IS_ACTIVE)
    return;*/


if (!class_exists('Semilon_Order_Filters_Support')) {
    class Semilon_Order_Filters_Support
    {
        public function __construct()
        {
            add_filter( 'plugin_action_links_' . SEMILON_ORDER_FILTERS_PLUGIN_BASENAME, array( $this, 'action_links' ) );
            add_action( 'admin_menu', array($this, 'add_support_to_admin_menu_item') );
        }

        public function add_support_to_admin_menu_item()
        {
            add_menu_page(
                __( 'Semilon support page', SEMILON_ORDER_FILTERS_TRANSLATE_ID ),
                __( 'Semilon menu', SEMILON_ORDER_FILTERS_TRANSLATE_ID ),
                'manage_options',
                'semilon-support',
                array($this, 'support_template'),
                'dashicons-schedule',
                3
            );
            remove_menu_page( 'semilon-support' );
        }

        public function support_template() {
            echo '<br /><br /><br /><center><h1 style="color: green">WoW!!!</h1><h2 style="color: red">Semilon support page</h2></center>';
        }

        /**
         * Add action links under WordPress > Plugins
         *
         * @param $links
         * @return array
         */
        public function action_links( $links ) {
            $plugin_links[] = '<a href="'
                . admin_url( 'admin.php?page=semilon-support' ) . '">'
                . __('Support', SEMILON_ORDER_FILTERS_TRANSLATE_ID) . '</a>';

            return array_merge( $plugin_links, $links );

        }
    }
}


