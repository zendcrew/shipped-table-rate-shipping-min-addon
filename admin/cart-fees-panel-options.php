<?php

if ( !class_exists( 'Reon' ) ) {
    return;
}

if ( !class_exists( 'WTARS_Shipped_Admin_Cart_Fees_Panel_Options_Min' ) ) {

    class WTARS_Shipped_Admin_Cart_Fees_Panel_Options_Min {

        public function __construct() {

            add_filter( 'wtars_shipped_admin/cart-fees/get-panel-option-fields', array( $this, 'get_panel_fields' ), 10, 100 );
        }

        public function get_panel_fields( $in_fields, $args ) {

            $in_fields[] = array(
                'id' => 'min_cost',
                'type' => 'columns-field',
                'columns' => 5,
                'merge_fields' => true,
                'fields' => $this->get_fields( $args ),
            );

            return $in_fields;
        }

        private function get_fields( $args ) {

            $in_fields = array();

            $in_fields[] = array(
                'id' => 'enable',
                'type' => 'select2',
                'column_size' => 2,
                'column_title' => esc_html__( 'Apply Minimum Fee', 'shipped-table-rate-min-shipping' ),
                'tooltip' => esc_html__( 'Choose whether or no minimum shipping fee should be applied', 'shipped-table-rate-min-shipping' ),
                'default' => 'no',
                'options' => array(
                    'no' => esc_html__( 'No, Do not apply minimum fee', 'shipped-table-rate-min-shipping' ),
                    'fixed_cost' => esc_html__( 'Yes, Apply fixed minimum fee', 'shipped-table-rate-min-shipping' ),
                    'per_cost' => esc_html__( 'Yes, Apply percentage minimum fee', 'shipped-table-rate-min-shipping' ),
                ),
                'width' => '100%',
                'fold_id' => 'min_cost',
            );

            $in_fields[] = array(
                'id' => 'cost',
                'type' => 'textbox',
                'input_type' => 'number',
                'column_size' => 1,
                'column_title' => esc_html__( 'Minimum Fee', 'shipped-table-rate-min-shipping' ),
                'tooltip' => esc_html__( 'Specify the minimum shipping fee', 'shipped-table-rate-min-shipping' ),
                'default' => '',
                'placeholder' => esc_html__( '0.00', 'shipped-table-rate-min-shipping' ),
                'width' => '100%',
                'attributes' => array(
                    'min' => '0',
                    'step' => '0.01',
                ),
                'fold' => array(
                    'target' => 'min_cost',
                    'attribute' => 'value',
                    'value' => 'no',
                    'oparator' => 'neq',
                    'clear' => true,
                ),
            );

            $in_fields[] = array(
                'id' => 'based_on',
                'type' => 'select2',
                'column_size' => 2,
                'column_title' => esc_html__( 'Percentage - Based On', 'shipped-table-rate-min-shipping' ),
                'tooltip' => esc_html__( 'Choose the basis for the percentage-based minimum shipping fee calculation', 'shipped-table-rate-min-shipping' ),
                'default' => '2234343',
                'data' => 'shipped:checkout_totals',
                'width' => '100%',
                'fold' => array(
                    'target' => 'min_cost',
                    'attribute' => 'value',
                    'value' => 'per_cost',
                    'oparator' => 'eq',
                    'clear' => true,
                    'empty' => '2234343',
                ),
            );

            return $in_fields;
        }

    }

    new WTARS_Shipped_Admin_Cart_Fees_Panel_Options_Min();
}

