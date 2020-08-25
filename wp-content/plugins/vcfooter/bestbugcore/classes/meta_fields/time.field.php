<tr <?php if( isset($option['tab']) && !empty($option['tab'])): ?> tab="tab" tab-element="<?php esc_html_e($option['tab']['element']);?>" tab-value="<?php esc_html_e( implode(',',$option['tab']['value']));?>" <?php endif;?>> 
    <th scope="row">
        <label for="<?php echo esc_html( $option['param_name']); ?>"><?php echo esc_html( $option['heading']); ?></label>
    </th>
    <td>
        <p>
        <input type="time" class="" id="<?php echo esc_html( $option['param_name']); ?>" name="<?php echo esc_html( $option['param_name']); ?>" value="<?php echo esc_html( $option['value']); ?>">
        </p>
        <p class="description"><?php echo ( $option['description']); ?></p>
    </td>
</tr>
    
