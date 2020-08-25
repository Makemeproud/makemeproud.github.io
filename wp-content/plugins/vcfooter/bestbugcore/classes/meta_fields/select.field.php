<tr <?php if( isset($option['tab']) && !empty($option['tab'])): ?> tab="tab" tab-element="<?php esc_html_e($option['tab']['element']);?>" tab-value="<?php esc_html_e( implode(',',$option['tab']['value']));?>" <?php endif;?>> 
    <th scope="row">
        <label for="<?php echo esc_html( $option['param_name'] ); ?>"><?php echo esc_html( $option['heading']); ?></label>
    </th>
    <td>
        <select id="<?php echo esc_html( $option['param_name'] ); ?>" name="<?php echo esc_html( $option['param_name'] ); ?>">
        <?php
        if (isset($option['options'])&& !empty($option['options'])) {
            foreach ($option['options'] as $optionKey => $optionVal) {
            ?>
            <option value="<?php echo esc_html( $optionKey); ?>" <?php if($option['value']==$optionKey): echo esc_html('selected '); endif;?> ><?php echo esc_html( $optionVal); ?></option>
            <?php
            }
        }
        ?>
        </select>
        <p class="description"><?php echo ( $option['description']); ?></p>
    </td>
</tr>
    
