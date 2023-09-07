<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}


if ( !class_exists( 'WTARS_Shipped_Shipping_Cost_Calc_Min' ) ) {

    class WTARS_Shipped_Shipping_Cost_Calc_Min {

        private static $instance;

        public static function get_instance(): self {

            if ( !self::$instance ) {

                self::$instance = new self();
            }

            return self::$instance;
        }

        public function get_min_cost( $cost, $min_args ) {

            if ( $this->get_cost_to_compare( $cost, $min_args ) >= $min_args[ 'cost' ] ) {

                return $cost;
            }

            $new_cost = $min_args[ 'cost' ];

            $new_taxes = $this->calculate_new_taxes( $cost, $min_args );

            $new_tax_total = $this->merge_taxes( $new_taxes );

            $cost[ 'cost' ] = $new_cost;
            $cost[ 'tax_total' ] = $new_tax_total;
            $cost[ 'taxes' ] = $new_taxes;

            if ( 'yes' == $min_args[ 'inclusive_tax' ] ) {

                $cost[ 'cost' ] = $new_cost - $new_tax_total;
            }

            return $cost;
        }

        private function calculate_new_taxes( $cost, $min_args ) {

            $new_tax_rates = $this->get_new_tax_rates( $this->get_cost_to_compare( $cost, $min_args ), $cost[ 'taxes' ] );

            $new_total = $min_args[ 'cost' ];

            $new_taxes = array();

            foreach ( $new_tax_rates as $key => $new_tax_rate ) {

                $new_taxes[ $key ] = $new_tax_rate * $new_total;
            }

            return $new_taxes;
        }

        private function get_new_tax_rates( $total, $tax_rates ) {

            $new_tax_rates = array();

            foreach ( $tax_rates as $key => $tax ) {

                $new_tax_rates[ $key ] = ($tax / $total);
            }

            return $new_tax_rates;
        }

        private function get_cost_to_compare( $cost, $min_args ) {

            if ( 'yes' == $min_args[ 'inclusive_tax' ] ) {

                return $cost[ 'cost' ] + $cost[ 'tax_total' ];
            }

            return $cost[ 'cost' ];
        }

        private function merge_taxes( $taxes ) {

            $tax_total = 0;

            foreach ( $taxes as $tax ) {

                $tax_total += $tax;
            }

            return $tax_total;
        }

    }

}

