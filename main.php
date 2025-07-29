<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( !class_exists( 'WTARS_Shipped_Min_Main' ) ) {

    class WTARS_Shipped_Min_Main {

        private static $instance;

        public static function get_instance(): self {

            if ( !self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        public function __construct() {

            if ( is_admin() ) {

                add_action( 'init', array( $this, 'load_admin_page' ) );
            }

            add_action( 'woocommerce_init', array( $this, 'init_public' ) );
        }

        public function load_admin_page() {

            require_once dirname( __FILE__ ) . '/admin/shipping-rates-panel-options.php';
            require_once dirname( __FILE__ ) . '/admin/cart-fees-panel-options.php';
        }

        public function init_public() {

            require_once dirname( __FILE__ ) . '/public/cost-calculator.php';
            require_once dirname( __FILE__ ) . '/public/shipping-rates-engine.php';
            require_once dirname( __FILE__ ) . '/public/cart-fees-engine.php';
        }

    }

}
