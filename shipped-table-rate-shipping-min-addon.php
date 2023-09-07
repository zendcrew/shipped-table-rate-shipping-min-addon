<?php

/*
 * Plugin Name: Shipped Minimum Shipping Costs (Add-On)
 * Plugin URI: https://codecanyon.net/item/woocommerce-table-rate-shipping/39691473?ref=zendcrew
 * Description: Sets minimum shipping costs and fees
 * Version: 1.0
 * Author: zendcrew
 * Author URI: https://codecanyon.net/user/zendcrew/portfolio?ref=zendcrew
 * Text Domain: shipped-table-rate-min-shipping
 * Domain Path: /languages/
 * Requires at least: 5.8
 * Tested up to: 6.2.2
 * Requires PHP: 5.6
 * 
 * WC requires at least: 5.6
 * WC tested up to: 7.9
 */

if ( !defined( 'WTARS_SHIPPED_MIN_FILE' ) ) {

    define( 'WTARS_SHIPPED_MIN_FILE', __FILE__ );
}


if ( !class_exists( 'WTARS_Shipped_Min_Init' ) ) {

    class WTARS_Shipped_Min_Init {

        public function __construct() {

            add_action( 'plugins_loaded', array( $this, 'plugin_loaded' ), 3 );

            add_action( 'before_woocommerce_init', array( $this, 'before_woocommerce_init' ) );

            load_plugin_textdomain( 'shipped-table-rate-min-shipping', false, dirname( plugin_basename( WTARS_SHIPPED_MIN_FILE ) ) . '/languages/' );
        }

        public function plugin_loaded() {
            
            if ( !defined( 'WTARS_SHIPPED_PREMIUM_FILE' ) ) { // check for premium version
                
                return;
            }

            if ( function_exists( 'WC' ) ) { // Check whether WooCommerce is active
                
                $this->main();
            }
        }

        public function before_woocommerce_init() {

            // Check for HPOS
            if ( !class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {

                return;
            }

            // Adds support for HPOS
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', WTARS_SHIPPED_MIN_FILE, true );
        }

        private function main() {

            //WTARS_Shipped_Min Main
            if ( !class_exists( 'WTARS_Shipped_Min_Main' ) ) {

                include_once ('main.php');

                WTARS_Shipped_Min_Main::get_instance();
            }
        }

    }

    new WTARS_Shipped_Min_Init();
}