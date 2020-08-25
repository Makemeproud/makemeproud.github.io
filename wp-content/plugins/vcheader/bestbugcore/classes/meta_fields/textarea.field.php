<tr <?php if( isset($option['tab']) && !empty($option['tab'])): ?> tab="tab" tab-element="<?php esc_html_e($option['tab']['element']);?>" tab-value="<?php esc_html_e( implode(',',$option['tab']['value']));?>" <?php endif;?>> 
    <th scope="row">
        <label for="<?php echo esc_html( $option['param_name']); ?>"><?php echo esc_html( $option['heading']); ?></label>
    </th>
    <td>
        <p>
            <textarea class="widefat"  name="<?php echo esc_html( $option['param_name']); ?>" id="<?php echo esc_html( $option['param_name']); ?>" cols="<?php echo esc_html( $option['cols']); ?>" rows="<?php echo esc_html( $option['rows']); ?>"><?php echo esc_html( $option['value']); ?></textarea>
        </p>
        <p class="description"><?php echo ( $option['description']); ?></p>
    </td>
</tr>