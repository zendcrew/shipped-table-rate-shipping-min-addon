<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}


if ( !class_exists( 'WTARS_Shipped_Shipping_Rates_Engine_Min' ) ) {

    class WTARS_Shipped_Shipping_Rates_Engine_Min {

        private $cost_calc;

        public function __construct() {

            $this->cost_calc = WTARS_Shipped_Shipping_Cost_Calc_Min::get_instance();

            add_filter( 'wtars_shipped/shipping-rates/calculated-cost', array( $this, 'get_calculated_cost' ), 10, 3 );
        }

        public function get_calculated_cost( $cost, $rate_id, $data ) {

            $min_args = $this->get_min_args( $rate_id, $data );

            if ( !$min_args ) {

                return $cost;
            }

            return $this->cost_calc->get_min_cost( $cost, $min_args );
        }

        private function get_min_args( $rate_id, $data ) {

            if ( !isset( $data[ 'wc' ][ 'instance_id' ] ) ) {

                return false;
            }

            $instance_id = $data[ 'wc' ][ 'instance_id' ];

            $min_options = $this->get_min_options( $rate_id, $instance_id );

            if ( !$min_options ) {

                return false;
            }

            $min_args = array(
                'inclusive_tax' => $min_options[ 'inclusive_tax' ],
            );

            $cost = $this->get_min_cost( $min_options, $data );

            if ( 0 >= $cost ) {

                return false;
            }

            $min_args[ 'cost' ] = $cost;

            return $min_args;
        }

        private function get_min_cost( $min_options, $data ) {

            if ( 'per_cost' == $min_options[ 'cost_type' ] ) {

                $args = $this->get_module_args( $min_options, $data );

                return $this->get_per_total( $min_options[ 'cost' ], $args, $data );
            }

            return $min_options[ 'cost' ];
        }

        private function get_per_total( $cost, $args, $data ) {

            $totals = WTARS_Shipped_Total_Types::get_totals( $args, $data );

            if ( $totals > 0 ) {
                return (($cost / 100) * $totals);
            }

            return 0;
        }

        private function get_min_options( $rate_id, $instance_id ) {

            $options = $this->get_options( $rate_id, $instance_id );

            if ( !$options ) {

                return false;
            }

            if ( !isset( $options[ 'min_cost' ] ) ) {

                return false;
            }

            if ( 'no' == $options[ 'min_cost' ][ 'enable' ] ) {

                return false;
            }

            if ( !is_numeric( $options[ 'min_cost' ][ 'cost' ] ) ) {

                return false;
            }

            $cost = ( float ) $options[ 'min_cost' ][ 'cost' ];

            if ( 0 >= $cost ) {

                return false;
            }

            $min_options = array(
                'inclusive_tax' => $options[ 'inclusive_tax' ],
                'cost_type' => $options[ 'min_cost' ][ 'enable' ],
                'cost' => $cost,
                'based_on' => $options[ 'min_cost' ][ 'based_on' ],
            );

            return $min_options;
        }

        private function get_module_args( $min_options, $data ) {

            return array(
                'module' => 'shipping-rates',
                'sub_module' => 'rates',
                'instance_id' => $data[ 'wc' ][ 'instance_id' ],
                'option_id' => $min_options[ 'based_on' ]
            );
        }

        private function get_options( $rate_id, $instance_id ) {

            foreach ( WTARS_Shipped::get_option( 'shipping_rates', $instance_id, array() ) as $rate_option ) {

                if ( $rate_id == $rate_option[ 'rate_id' ] ) {

                    return $rate_option;
                }
            }

            return false;
        }

    }

    new WTARS_Shipped_Shipping_Rates_Engine_Min();
}
