<?php
/** 
 * Type: Code snippet for woocommerce. Can be added to function.php file of the active child theme (or active theme) or in any plugin file.
 * Description: Add Select field with option group new form field type "select_og" to WooCommerce available form field types.
 * Author: LoicTheAztec
 *
 * Field type: select_og
 */
add_filter('woocommerce_form_field_select_og', 'add_form_field_type_select_with_option_group', 10, 4 );
function add_form_field_type_select_with_option_group( $field, $key, $args, $value ) {
    if ( $args['required'] ) {
        $args['class'][] = 'validate-required';
        $required        = ' <abbr class="required" title="' . esc_attr__( 'required', 'woocommerce' ) . '">*</abbr>';
    } else {
        $required = '';
    }

    if ( is_string( $args['label_class'] ) ) {
        $args['label_class'] = array( $args['label_class'] );
    }

    if ( is_null( $value ) ) {
        $value = $args['default'];
    }

    // Custom attribute handling.
    $custom_attributes         = array();
    $args['custom_attributes'] = array_filter( (array) $args['custom_attributes'], 'strlen' );

    if ( $args['maxlength'] ) {
        $args['custom_attributes']['maxlength'] = absint( $args['maxlength'] );
    }

    if ( ! empty( $args['autocomplete'] ) ) {
        $args['custom_attributes']['autocomplete'] = $args['autocomplete'];
    }

    if ( true === $args['autofocus'] ) {
        $args['custom_attributes']['autofocus'] = 'autofocus';
    }

    if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
        foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
            $custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
        }
    }

    if ( ! empty( $args['validate'] ) ) {
        foreach ( $args['validate'] as $validate ) {
            $args['class'][] = 'validate-' . $validate;
        }
    }

    $field           = '';
    $label_id        = $args['id'];
    $sort            = $args['priority'] ? $args['priority'] : '';
    $field_container = '<p class="form-row %1$s" id="%2$s" data-priority="' . esc_attr( $sort ) . '">%3$s</p>';
    $options         = '';

    if ( ! empty( $args['options'] ) ) {
	    // First loop: Options group
        foreach ( $args['options'] as $option_group => $option_values ) {
            if ( '' === $option_group ) {
                // If we have a blank option, select2 needs a placeholder.
                if ( empty( $args['placeholder'] ) ) {
                    $args['placeholder'] = $option_values ? $option_values : __( 'Choose an option', 'woocommerce' );
                }
                $custom_attributes[] = 'data-allow_clear="true"';

                $options .= '<option value="' . esc_attr( $option_group ) . '">' . esc_attr( $option_values ) . '</option>';
            } else {
	            $options .= '<optgroup label="'. esc_attr( $option_group ) . '">';

				// Second loop: Options in an otion group
				foreach ( $option_values as $option_key => $option_text ) {
	            	$options .= '<option value="' . esc_attr( $option_key ) . '" ' . selected( $value, $option_key, false ) . '>' . esc_attr( $option_text ) . '</option>';
	            }

	            $options .= '</optgroup>';
            }
        }

        $field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . ' data-placeholder="' . esc_attr( $args['placeholder'] ) . '">
                ' . $options . '
            </select>';
    }

    if ( ! empty( $field ) ) {
        $field_html = '';

        if ( $args['label'] && 'checkbox' !== $args['type'] ) {
            $field_html .= '<label for="' . esc_attr( $label_id ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) . '">' . $args['label'] . $required . '</label>';
        }

        $field_html .= $field;

        if ( $args['description'] ) {
            $field_html .= '<span class="description">' . esc_html( $args['description'] ) . '</span>';
        }

        $container_class = esc_attr( implode( ' ', $args['class'] ) );
        $container_id    = esc_attr( $args['id'] ) . '_field';
        $field           = sprintf( $field_container, $container_class, $container_id, $field_html );
    }

    return $field;
}
